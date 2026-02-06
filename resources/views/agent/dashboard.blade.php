@extends('layouts.app')

@section('title', 'Agent Dashboard')

@php
    $color = 'purple';
    $title = 'Agent Dashboard';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('agent.dashboard'), 'active' => true],
        ['label' => 'Scan-1 (Shipment)', 'url' => route('agent.scan.shipment'), 'active' => false],
    ];
@endphp

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome, {{ auth('agent')->user()->name }}!</h2>
    <p class="text-gray-600">Scan operations are listed below with pending items.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <a href="{{ route('agent.scan.shipment') }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-2 border-transparent hover:border-purple-300">
        <h3 class="text-xl font-bold text-purple-600 mb-2">Scan-1</h3>
        <p class="text-gray-600 text-sm mb-3">Receive Shipment</p>
        <div class="text-xs text-gray-500">
            Status: pending → received_in_warehouse
        </div>
    </a>
<!--     
    <a href="{{ route('agent.scan.order-prep') }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-2 border-transparent hover:border-purple-300">
        <h3 class="text-xl font-bold text-purple-600 mb-2">Scan-2</h3>
        <p class="text-gray-600 text-sm mb-3">Prepare Order</p>
        <div class="text-xs text-gray-500">
            Status: pending_scan2 → prepared_for_delivery
        </div>
    </a>
    
    <a href="{{ route('agent.scan.order-handover') }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-2 border-transparent hover:border-purple-300">
        <h3 class="text-xl font-bold text-purple-600 mb-2">Scan-3</h3>
        <p class="text-gray-600 text-sm mb-3">Handover to Delivery</p>
        <div class="text-xs text-red-600 font-semibold">
            ⚠️ Triggers Settlement!
        </div>
    </a> -->
</div>

<!-- Pending Shipments for Scan-1 -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Pending Shipments (Scan-1 Required)</h2>
    </div>
    @if($pendingShipments->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tracking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipment Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Group Id</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Partner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pendingShipments as $shipment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('agent.scan.shipment', ['tracking_id' => $shipment->tracking_id]) }}" class="font-mono font-semibold text-purple-600 hover:text-purple-800 hover:underline">
                            {{ $shipment->tracking_id }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-700">{{ $shipment->shipment_code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $shipment->product_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->client->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->client->group_id }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                            {{ $shipment->delivery_partner }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $shipment->quantity_total }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-6 text-center text-gray-500">
        No pending shipments. All shipments have been received.
    </div>
    @endif
</div>

<!-- Pending Orders for Scan-2 -->
<!-- <div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Orders Pending Preparation (Scan-2 Required)</h2>
    </div>
    @if($pendingScan2->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pendingScan2 as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-mono font-semibold text-purple-600">{{ $order->order_code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->shipment->shipment_code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->quantity }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-6 text-center text-gray-500">
        No orders pending preparation. All orders are ready for delivery.
    </div>
    @endif
</div> -->

<!-- Orders Ready for Scan-3 -->
<!-- <div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Orders Ready for Handover (Scan-3 Required)</h2>
    </div>
    @if($pendingScan3->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prepared</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pendingScan3 as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-mono font-semibold text-purple-600">{{ $order->order_code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->shipment->shipment_code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->quantity }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->scan2_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-6 text-center text-gray-500">
        No orders ready for handover. Complete Scan-2 first.
    </div>
    @endif
</div> -->
@endsection
