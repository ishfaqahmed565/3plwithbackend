<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ShipmentService;
use App\Models\ShipmentAttachment;
use Illuminate\Support\Arr;
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
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
            'quantity_total' => 'required|integer|min:1',
            'tracking_id' => 'required|string|max:255|unique:shipments,tracking_id',
            'delivery_partner' => 'required|in:FEDEX,UPS,AMAZON,USPS,DHL,Other',
        ]);

        $validated['client_id'] = Auth::guard('client')->id();

        $shipment = $this->shipmentService->createShipment(
            $validated,
            $request->file('product_image'),
            $request->file('attachments', [])
        );

        return redirect()->route('client.shipments.index')
            ->with('success', 'Shipment created successfully! Code: ' . $shipment->shipment_code);
    }

    public function show($id)
    {
        $shipment = Auth::guard('client')->user()->shipments()->with(['attachments', 'lineItems', 'orders', 'rackLocation'])->findOrFail($id);
        return view('client.shipments.show', compact('shipment'));
    }

    public function edit($id)
    {
        $shipment = Auth::guard('client')->user()->shipments()->with('attachments')->findOrFail($id);

        if ($shipment->status === 'received_in_warehouse') {
            return redirect()->route('client.shipments.show', $shipment)->with('error', 'Received shipments cannot be edited.');
        }

        return view('client.shipments.edit', compact('shipment'));
    }

    public function update(Request $request, $id)
    {
        $shipment = Auth::guard('client')->user()->shipments()->with('attachments')->findOrFail($id);

        if ($shipment->status === 'received_in_warehouse') {
            return redirect()->route('client.shipments.show', $shipment)->with('error', 'Received shipments cannot be edited.');
        }

        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
            'quantity_total' => 'required|integer|min:1',
            'tracking_id' => 'required|string|max:255|unique:shipments,tracking_id,' . $shipment->id,
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

        return redirect()->route('client.shipments.show', $shipment)->with('success', 'Shipment updated successfully!');
    }
}
