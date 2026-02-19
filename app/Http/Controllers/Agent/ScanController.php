<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\RackLocation;
use App\Services\ShipmentService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function __construct(
        private ShipmentService $shipmentService,
        private OrderService $orderService
    ) {
    }

    // View Shipment Details
    public function showShipment($id)
    {
        $shipment = Shipment::with(['client', 'rackLocation', 'orders', 'attachments', 'lineItems', 'products'])->findOrFail($id);
        return view('agent.shipments.show', compact('shipment'));
    }

    // Scan-1: Shipment Receiving
    public function scanShipment(Request $request)
    {
        // If tracking_id is provided, lookup and show verification form
        if ($request->has('tracking_id')) {
            $shipment = Shipment::where('tracking_id', $request->tracking_id)
                ->with(['client', 'products'])
                ->first();
            
            if (!$shipment) {
                // Show modal to confirm creating unknown shipment
                $trackingId = $request->tracking_id;
                return view('agent.scan-shipment', compact('trackingId'));
            }
            
            if ($shipment->status === 'received_in_warehouse') {
                return back()->with('warning', 'Shipment already received!');
            }
            
            return view('agent.scan-shipment', compact('shipment'));
        }
        
        // Otherwise show lookup form
        return view('agent.scan-shipment');
    }

    public function processScan1(Request $request)
    {
        $validated = $request->validate([
            'tracking_id' => 'required|string|exists:shipments,tracking_id',
            'rack_location' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:shipment_products,id',
            'products.*.quantity_received' => 'required|integer|min:1',
            'products.*.condition' => 'required|in:excellent,good,fair,damaged',
            'products.*.notes' => 'nullable|string|max:500',
            'scan1_notes' => 'nullable|string|max:1000',
            'scan1_files' => 'required|array|min:1',
            'scan1_files.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
            'line_items' => 'nullable|array',
            'line_items.*' => 'nullable|string|max:255',
        ]);

        // Additional validation: quantity received can't exceed expected
        foreach ($validated['products'] as $productData) {
            $product = \App\Models\ShipmentProduct::findOrFail($productData['id']);
            if ($productData['quantity_received'] > $product->quantity_expected) {
                return back()->withErrors([
                    'products' => "Received quantity for {$product->name} cannot exceed expected quantity ({$product->quantity_expected})"
                ])->withInput();
            }
        }

        $shipment = Shipment::where('tracking_id', $validated['tracking_id'])->with('products')->firstOrFail();

        if ($shipment->status === 'received_in_warehouse') {
            return back()->with('warning', 'Shipment already received!');
        }

        // Update each product with received quantity, condition, and notes
        foreach ($validated['products'] as $productData) {
            $product = \App\Models\ShipmentProduct::findOrFail($productData['id']);
            $product->update([
                'quantity_available' => $productData['quantity_received'],
                'product_condition' => $productData['condition'],
                'notes' => $productData['notes'] ?? null,
            ]);
        }

        // Calculate total received quantity
        $totalReceivedQuantity = array_sum(array_column($validated['products'], 'quantity_received'));
        
        // Determine overall product condition (worst condition among all products)
        $conditions = ['excellent' => 4, 'good' => 3, 'fair' => 2, 'damaged' => 1];
        $overallCondition = 'excellent';
        foreach ($validated['products'] as $productData) {
            if ($conditions[$productData['condition']] < $conditions[$overallCondition]) {
                $overallCondition = $productData['condition'];
            }
        }

        $this->shipmentService->receiveShipmentWithVerification(
            $shipment,
            $validated['rack_location'] ?? null,
            $totalReceivedQuantity,
            $overallCondition,
            $validated['scan1_notes'] ?? null,
            $request->file('scan1_files', []),
            $validated['line_items'] ?? [],
            auth('agent')->id(),
            auth('agent')->user()->warehouse
        );

        return redirect()->route('agent.dashboard')->with('success', 'Scan-1 Complete: Shipment ' . $shipment->shipment_code . ' received and verified!');
    }

    // Scan-2: Order Preparation
    public function scanOrderPrep()
    {
        return view('agent.scan-order-prep');
    }

    public function processScan2(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
        ]);

        $order = Order::where('order_code', $request->order_code)->firstOrFail();

        try {
            $this->orderService->performScan2($order);
            return back()->with('success', 'Scan-2 Complete: Order ' . $order->order_code . ' prepared for delivery!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Scan-3: Handover to Delivery Partner
    public function scanOrderHandover()
    {
        return view('agent.scan-order-handover');
    }

    public function processScan3(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
        ]);

        $order = Order::where('order_code', $request->order_code)->firstOrFail();

        try {
            $this->orderService->performScan3($order);
            return back()->with('success', 'Scan-3 Complete: Order ' . $order->order_code . ' handed over! Settlement created.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
