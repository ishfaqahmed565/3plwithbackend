@extends('layouts.app')

@section('title', 'Agent Dashboard')

@php
    $color = 'purple';
    $title = 'Agent Dashboard';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    // $navigation is now provided by AgentNavigationComposer
@endphp

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome, {{ auth('agent')->user()->name }}!</h2>
    <p class="text-gray-600">Scan operations are listed below with pending items.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Pending Shipments</h3>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Awaiting Scan-1</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Received Shipments</h3>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['received_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">In Warehouse</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">No Tracking ID</h3>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['no_tracking_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Pending shipments</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">No Rack Assignment</h3>
        <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['no_rack_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Received shipments</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Unassigned Shipments</h3>
        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['unassigned_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">No client assigned</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <a href="{{ route('agent.scan.shipment') }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-2 border-transparent hover:border-purple-300">
        <h3 class="text-xl font-bold text-purple-600 mb-2">Scan-1</h3>
        <p class="text-gray-600 text-sm mb-3">Receive Shipment</p>
        <div class="text-xs text-gray-500">
            Status: pending → received_in_warehouse
        </div>
    </a>
    
    <a href="{{ route('agent.shipments.create-unknown') }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-2 border-transparent hover:border-orange-300">
        <h3 class="text-xl font-bold text-orange-600 mb-2">Create Unknown Shipment</h3>
        <p class="text-gray-600 text-sm mb-3">Register unidentified packages</p>
        <div class="text-xs text-gray-500">
            For shipments without client info
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
