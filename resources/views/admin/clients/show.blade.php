@extends('layouts.app')

@section('title', 'Client Details')

@php
    $color = 'blue';
    $title = 'Client Details';
    $userName = auth('admin')->user()->name;
    $logoutRoute = route('admin.logout');
    // $navigation is now provided by AdminNavigationComposer
@endphp

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.clients.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Clients</a>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <h3 class="text-sm font-medium text-gray-500">Client Name</h3>
            <p class="text-lg font-semibold text-gray-900 mt-1">{{ $client->name }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Email</h3>
            <p class="text-lg text-gray-900 mt-1">{{ $client->email }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Group ID</h3>
            <p class="text-lg font-mono font-semibold text-blue-600 mt-1">{{ $client->group_id }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Shipments</h3>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $client->shipments->count() }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $client->orders->count() }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Pending Settlements</h3>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ $client->settlements->where('status', 'pending')->count() }}</p>
    </div>
</div>

<!-- Shipments -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Shipments</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipment Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Available</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($client->shipments as $shipment)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono">{{ $shipment->shipment_code }}</td>
                    <td class="px-6 py-4 text-sm">{{ $shipment->product_name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $shipment->quantity_total }}</td>
                    <td class="px-6 py-4 text-sm">{{ $shipment->quantity_available }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($shipment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($shipment->status === 'received_in_warehouse') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ str_replace('_', ' ', ucfirst($shipment->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No shipments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Orders -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Orders</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($client->orders as $order)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono">{{ $order->order_code }}</td>
                    <td class="px-6 py-4 text-sm">{{ $order->shipment->shipment_code }}</td>
                    <td class="px-6 py-4 text-sm">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $order->quantity }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($order->status === 'pending_scan2') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'prepared_for_delivery') bg-blue-100 text-blue-800
                            @elseif($order->status === 'handover_to_delivery_partner') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ str_replace('_', ' ', ucfirst($order->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
