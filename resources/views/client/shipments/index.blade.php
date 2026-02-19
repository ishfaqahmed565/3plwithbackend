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
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Status:</label>
                <select id="statusFilterClient" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="received_in_warehouse">Received</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Tracking:</label>
                <select id="trackingFilterClient" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="all">All</option>
                    <option value="no-tracking">No Tracking ID</option>
                    <option value="with-tracking">With Tracking ID</option>
                </select>
            </div>
        </div>
    </div>
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
                <tr class="hover:bg-gray-50 client-shipment-row" 
                    data-status="{{ $shipment->status }}" 
                    data-has-tracking="{{ $shipment->tracking_id ? 'yes' : 'no' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('client.shipments.show', $shipment) }}" class="text-green-600 hover:text-green-800 hover:underline font-mono">
                            {{ $shipment->shipment_code }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $shipment->product_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $shipment->quantity_total }}</td>
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
                        @if($shipment->status === 'pending')
                        <span class="mx-2 text-gray-300">|</span>
                        <a href="{{ route('client.shipments.edit', $shipment) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Edit
                        </a>
                        @endif
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilterClient');
    const trackingFilter = document.getElementById('trackingFilterClient');
    const rows = document.querySelectorAll('.client-shipment-row');
    
    function applyFilters() {
        const statusValue = statusFilter.value;
        const trackingValue = trackingFilter.value;
        
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const hasTracking = row.getAttribute('data-has-tracking');
            
            let showRow = true;
            
            // Apply status filter
            if (statusValue !== 'all' && status !== statusValue) {
                showRow = false;
            }
            
            // Apply tracking filter
            if (trackingValue === 'no-tracking' && hasTracking !== 'no') {
                showRow = false;
            } else if (trackingValue === 'with-tracking' && hasTracking !== 'yes') {
                showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    if (statusFilter && trackingFilter && rows.length > 0) {
        statusFilter.addEventListener('change', applyFilters);
        trackingFilter.addEventListener('change', applyFilters);
    }
});
</script>
@endsection
