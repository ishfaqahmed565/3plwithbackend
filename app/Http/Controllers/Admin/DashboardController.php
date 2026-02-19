<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => Client::count(),
            'total_shipments' => Shipment::count(),
            'total_orders' => Order::count(),
            'pending_settlements' => Settlement::where('status', 'pending')->count(),
            'pending_shipments' => Shipment::where('status', 'pending')->count(),
            'received_shipments' => Shipment::where('status', 'received_in_warehouse')->count(),
            'no_tracking_shipments' => Shipment::whereNull('tracking_id')->where('status', 'pending')->count(),
            'no_rack_shipments' => Shipment::where('status', 'received_in_warehouse')->whereNull('rack_location')->count(),
            'unassigned_shipments' => Shipment::whereNull('client_id')
                ->where(function($query) {
                    $query->whereNotNull('created_by_agent_id')
                          ->orWhereNotNull('created_by_admin_id');
                })
                ->count(),
        ];

        $allShipments = Shipment::with('client')->latest()->get();
        
        $noRackShipments = Shipment::where('status', 'received_in_warehouse')
            ->whereNull('rack_location')
            ->with(['client', 'receivedByAgent'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('stats', 'allShipments', 'noRackShipments'));
    }
    
    public function assignRack(Request $request, $id)
    {
        $validated = $request->validate([
            'rack_location' => 'required|string|max:255',
        ]);
        
        $shipment = Shipment::where('status', 'received_in_warehouse')
            ->whereNull('rack_location')
            ->findOrFail($id);
        
        $shipment->update([
            'rack_location' => $validated['rack_location'],
        ]);
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Rack location assigned successfully to shipment ' . $shipment->shipment_code);
    }
    
    public function createUnknownShipment()
    {
        $clients = Client::orderBy('name')->get();
        return view('admin.shipments.create-unknown', compact('clients'));
    }
    
    public function storeUnknownShipment(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'source' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'tracking_id' => 'required|string|max:255|unique:shipments,tracking_id',
            'delivery_partner' => 'required|in:FEDEX,UPS,AMAZON,USPS,DHL,Other',
            'rack_location' => 'nullable|string|max:255',
            'scan1_notes' => 'nullable|string',
            'scan1_files' => 'required|array|min:1',
            'scan1_files.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
            'line_items' => 'nullable|array',
            'line_items.*' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
        
        // Calculate total quantity from products
        $totalQuantity = collect($validated['products'])->sum('quantity');
        
        // Generate shipment code
        $validated['shipment_code'] = 'SH-' . strtoupper(uniqid());
        $validated['status'] = 'received_in_warehouse';
        $validated['created_by_admin_id'] = Auth::guard('admin')->id();
        $validated['scan1_at'] = now();
        $validated['quantity_total'] = $totalQuantity;
        $validated['quantity_available'] = $totalQuantity;
        
        $shipment = Shipment::create($validated);
        
        // Handle file uploads
        if ($request->hasFile('scan1_files')) {
            foreach ($request->file('scan1_files') as $file) {
                $path = $file->store('shipments/' . $shipment->id . '/scan1', 'public');
                $shipment->attachments()->create([
                    'file_path' => $path,
                    'uploaded_by' => 'admin',
                    'context' => 'scan1_proof',
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }
        
        // Handle line items
        if (!empty($validated['line_items'])) {
            foreach ($validated['line_items'] as $lineItem) {
                if (!empty($lineItem)) {
                    $shipment->lineItems()->create([
                        'barcode_value' => $lineItem,
                        'scanned_at' => now(),
                    ]);
                }
            }
        }
        
        // Handle products
        if (!empty($validated['products'])) {
            foreach ($validated['products'] as $product) {
                $shipment->products()->create([
                    'name' => $product['name'],
                    'description' => $product['description'] ?? null,
                    'quantity_expected' => $product['quantity'],
                    'quantity_available' => $product['quantity'],
                ]);
            }
        }
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Unknown shipment created and received successfully! Code: ' . $shipment->shipment_code);
    }
    
    public function editUnknownShipment($id)
    {
        $shipment = Shipment::with(['products', 'attachments', 'lineItems'])->findOrFail($id);
        $clients = \App\Models\Client::orderBy('name')->get();
        
        return view('admin.shipments.edit-unknown', compact('shipment', 'clients'));
    }
    
    public function updateUnknownShipment(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);
        
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'source' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'tracking_id' => 'required|string|max:255|unique:shipments,tracking_id,' . $id,
            'delivery_partner' => 'required|in:FEDEX,UPS,AMAZON,USPS,DHL,Other',
            'rack_location' => 'nullable|string|max:255',
            'scan1_notes' => 'nullable|string',
            'scan1_files' => 'nullable|array',
            'scan1_files.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
            'line_items' => 'nullable|array',
            'line_items.*' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
        
        // Calculate total quantity from products
        $totalQuantity = collect($validated['products'])->sum('quantity');
        $validated['quantity_total'] = $totalQuantity;
        $validated['quantity_available'] = $totalQuantity;
        
        $shipment->update($validated);
        
        // Handle file uploads if new files are uploaded
        if ($request->hasFile('scan1_files')) {
            foreach ($request->file('scan1_files') as $file) {
                $path = $file->store('shipments/' . $shipment->id . '/scan1', 'public');
                $shipment->attachments()->create([
                    'file_path' => $path,
                    'uploaded_by' => 'admin',
                    'context' => 'scan1_proof',
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }
        
        // Update line items - delete old and create new
        if ($request->has('line_items')) {
            $shipment->lineItems()->delete();
            if (!empty($validated['line_items'])) {
                foreach ($validated['line_items'] as $lineItem) {
                    if (!empty($lineItem)) {
                        $shipment->lineItems()->create([
                            'barcode_value' => $lineItem,
                            'scanned_at' => now(),
                        ]);
                    }
                }
            }
        }
        
        // Update products - delete old and create new
        if ($request->has('products')) {
            $shipment->products()->delete();
            if (!empty($validated['products'])) {
                foreach ($validated['products'] as $product) {
                    $shipment->products()->create([
                        'name' => $product['name'],
                        'description' => $product['description'] ?? null,
                        'quantity_expected' => $product['quantity'],
                        'quantity_available' => $product['quantity'],
                    ]);
                }
            }
        }
        
        return redirect()->route('admin.unknown-shipments')
            ->with('success', 'Unknown shipment updated successfully! Code: ' . $shipment->shipment_code);
    }
    
    // Separate table pages
    public function allShipments()
    {
        $allShipments = Shipment::with('client')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.all-shipments', compact('allShipments'));
    }
    
    public function noRackShipments()
    {
        $noRackShipments = Shipment::where('status', 'received_in_warehouse')
            ->whereNull('rack_location')
            ->with(['client', 'receivedByAgent'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.no-rack-shipments', compact('noRackShipments'));
    }
    
    public function unknownShipments()
    {
        $unknownShipments = Shipment::where(function($query) {
                $query->whereNotNull('created_by_agent_id')
                      ->orWhereNotNull('created_by_admin_id');
            })
            ->with(['client', 'createdByAgent', 'createdByAdmin', 'receivedByAgent'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.unknown-shipments', compact('unknownShipments'));
    }
}
