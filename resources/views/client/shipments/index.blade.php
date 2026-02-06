@extends('layouts.app')

@section('title', 'My Shipments')

@php
    $color = 'green';
    $title = 'My Shipments';
    $userName = auth('client')->user()->name;
    $logoutRoute = route('client.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('client.dashboard'), 'active' => false],
        ['label' => 'Shipments', 'url' => route('client.shipments.index'), 'active' => true],
    ];
@endphp

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900">My Shipments</h2>
    <a href="{{ route('client.shipments.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
        + Create Shipment
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Available</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($shipments as $shipment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('client.shipments.show', $shipment) }}" class="text-green-600 hover:text-green-800 hover:underline font-mono">
                            {{ $shipment->shipment_code }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $shipment->product_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $shipment->total_quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $shipment->quantity_available }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($shipment->status === 'pending')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Received</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $shipment->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('client.shipments.show', $shipment) }}" class="text-green-600 hover:text-green-800 font-semibold">
                            View Details
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No shipments found. <a href="{{ route('client.shipments.create') }}" class="text-green-600 hover:underline">Create your first shipment</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
