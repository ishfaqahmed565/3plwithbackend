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
        $shipment = Shipment::with(['client', 'rackLocation', 'orders', 'attachments', 'lineItems'])->findOrFail($id);
        return view('agent.shipments.show', compact('shipment'));
    }

    // Scan-1: Shipment Receiving
    public function scanShipment(Request $request)
    {
        // If tracking_id is provided, lookup and show verification form
        if ($request->has('tracking_id')) {
            $shipment = Shipment::where('tracking_id', $request->tracking_id)
                ->with('client')
                ->first();
            
            if (!$shipment) {
                return back()->with('error', 'Shipment not found with tracking ID: ' . $request->tracking_id);
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
            'rack_location' => 'required|string|max:255',
            'received_quantity' => 'required|integer|min:1',
            'product_condition' => 'required|in:excellent,good,fair,damaged',
            'scan1_notes' => 'nullable|string|max:1000',
            'scan1_files' => 'required|array|min:1',
            'scan1_files.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
            'line_items' => 'nullable|array',
            'line_items.*' => 'nullable|string|max:255',
        ]);

        $shipment = Shipment::where('tracking_id', $validated['tracking_id'])->firstOrFail();

        if ($shipment->status === 'received_in_warehouse') {
            return back()->with('warning', 'Shipment already received!');
        }

        $this->shipmentService->receiveShipmentWithVerification(
            $shipment,
            $validated['rack_location'],
            $validated['received_quantity'],
            $validated['product_condition'],
            $validated['scan1_notes'] ?? null,
            $request->file('scan1_files', []),
            $validated['line_items'] ?? []
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
