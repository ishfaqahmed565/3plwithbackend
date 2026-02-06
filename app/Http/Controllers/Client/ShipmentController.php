<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ShipmentService;
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
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'quantity_total' => 'required|integer|min:1',
            'tracking_id' => 'required|string|max:255|unique:shipments,tracking_id',
            'delivery_partner' => 'required|in:FEDEX,UPS,AMAZON,USPS,DHL,Other',
        ]);

        $validated['client_id'] = Auth::guard('client')->id();

        $shipment = $this->shipmentService->createShipment(
            $validated,
            $request->file('product_image')
        );

        return redirect()->route('client.shipments.index')
            ->with('success', 'Shipment created successfully! Code: ' . $shipment->shipment_code);
    }

    public function show($id)
    {
        $shipment = Auth::guard('client')->user()->shipments()->findOrFail($id);
        return view('client.shipments.show', compact('shipment'));
    }
}
