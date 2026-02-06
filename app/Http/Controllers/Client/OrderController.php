<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\ShipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private ShipmentService $shipmentService
    ) {
    }

    public function index()
    {
        $orders = $this->orderService->getClientOrders(Auth::guard('client')->id());
        return view('client.orders.index', compact('orders'));
    }

    public function create()
    {
        $shipments = $this->shipmentService->getAvailableInventory(Auth::guard('client')->id());
        return view('client.orders.create', compact('shipments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'tracking_id' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'label' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $validated['client_id'] = Auth::guard('client')->id();

        try {
            $order = $this->orderService->createOrder(
                $validated,
                $request->file('label')
            );

            return redirect()->route('client.orders.index')
                ->with('success', 'Order created successfully! Code: ' . $order->order_code);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $order = Auth::guard('client')->user()->orders()->with(['shipment'])->findOrFail($id);
        return view('client.orders.show', compact('order'));
    }
}
