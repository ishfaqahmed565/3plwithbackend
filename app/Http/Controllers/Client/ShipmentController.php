<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ShipmentService;
use App\Models\ShipmentAttachment;
use Illuminate\Support\Arr;
use App\Models\ShipmentProduct;
use App\Models\Shipment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function __construct(private ShipmentService $shipmentService)
    {
    }

    public function index()
    {
        $shipments = $this->shipmentService->getClientShipments(Auth::guard('client')->id());
        return view('client.shipments.index', compact('shipments'));
    }

    public function create()
    {
        return view('client.shipments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.quantity' => 'required|integer|min:1',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
            //'quantity_total' => 'required|integer|min:1',
            'tracking_id' => 'nullable|string|max:255|unique:shipments,tracking_id',
            'delivery_partner' => 'required|in:FEDEX,UPS,AMAZON,USPS,DHL,Other',
        ]);
        
        // Check if tracking ID exists and was created by agent/admin
        if (!empty($validated['tracking_id'])) {
            $existingShipment = Shipment::where('tracking_id', $validated['tracking_id'])
                ->whereNotNull('created_by_agent_id')
                ->orWhereNotNull('created_by_admin_id')
                ->first();
            
            if ($existingShipment) {
                return back()
                    ->withInput()
                    ->with('warning', 'This tracking ID was already registered by warehouse staff. Please contact support if this is your shipment.');
            }
        }

        $validated['client_id'] = Auth::guard('client')->id();

        $shipment = $this->shipmentService->createShipment(
            $validated,
            $request->file('product_image'),
            $request->file('attachments', []),
            $validated['products'] ?? []
        );

        return redirect()->route('client.shipments.index')
            ->with('success', 'Shipment created successfully! Code: ' . $shipment->shipment_code);
    }

    public function show($id)
    {
        $shipment = Auth::guard('client')->user()->shipments()->with(['attachments', 'lineItems', 'orders', 'rackLocation', 'products'])->findOrFail($id);
        return view('client.shipments.show', compact('shipment'));
    }

    public function edit($id)
    {
        $shipment = Auth::guard('client')->user()->shipments()->with(['attachments', 'products'])->findOrFail($id);

        return view('client.shipments.edit', compact('shipment'));
    }

    public function update(Request $request, $id)
    {
        $shipment = Auth::guard('client')->user()->shipments()->with('attachments')->findOrFail($id);

        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'products' => 'nullable|array',
            'products.*.name' => 'required_with:products|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.quantity' => 'required_with:products|integer|min:1',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
            'tracking_id' => 'nullable|string|max:255|unique:shipments,tracking_id,' . $shipment->id,
            'delivery_partner' => 'required|in:FEDEX,UPS,AMAZON,USPS,DHL,Other',
            'remove_attachments' => 'nullable|array',
            'remove_attachments.*' => 'integer',
        ]);

        $shipment->update(Arr::except($validated, ['remove_attachments']));

        if ($request->filled('remove_attachments')) {
            $attachmentsToRemove = ShipmentAttachment::where('shipment_id', $shipment->id)
                ->where('context', 'client_upload')
                ->whereIn('id', $request->input('remove_attachments', []))
                ->get();

            foreach ($attachmentsToRemove as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
                $attachment->delete();
            }
        }

        $newFiles = $request->file('attachments', []);
        if (!empty($newFiles)) {
            $this->shipmentService->storeAttachments($shipment, $newFiles, 'client', 'client_upload');
        }

        // handle products update: replace existing if provided
        if ($request->filled('products')) {
            $shipment->products()->delete();
            $products = $request->input('products', []);
            foreach ($products as $p) {
                ShipmentProduct::create([
                    'shipment_id' => $shipment->id,
                    'name' => $p['name'] ?? 'Unnamed',
                    'description' => $p['description'] ?? null,
                    'quantity_expected' => (int)($p['quantity'] ?? 0),
                    'quantity_available' => (int)($p['quantity'] ?? 0),
                ]);
            }
        }

        return redirect()->route('client.shipments.show', $shipment)->with('success', 'Shipment updated successfully!');
    }
}
