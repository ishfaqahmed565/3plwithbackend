@extends('layouts.app')

@section('title', 'All Shipments')

@php
    $color = 'blue';
    $title = 'All Shipments';
    $userName = auth('admin')->user()->name;
    $logoutRoute = route('admin.logout');
    // $navigation is now provided by AdminNavigationComposer
@endphp

@section('content')
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">All Shipments</h2>
                <p class="text-sm text-gray-600 mt-1">Complete list of all shipments in the system</p>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Status:</label>
                <select id="statusFilter" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="all">All</option>
                    <option value="pending">Pending</option>
                    <option value="received_in_warehouse">Received</option>
                </select>
            </div>
        </div>
    </div>
    @if($allShipments->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipment Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tracking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rack Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($allShipments as $shipment)
                <tr class="hover:bg-gray-50 shipment-row" data-status="{{ $shipment->status }}">
                    <td class="px-6 py-4 text-sm font-mono font-semibold text-blue-600">{{ $shipment->shipment_code }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-900">
                        @if($shipment->tracking_id)
                        {{ $shipment->tracking_id }}
                        @else
                        <span class="text-gray-400">No Tracking</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $shipment->product_description }}
                        <div class="text-xs text-gray-500">{{ $shipment->category }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $shipment->client?->name ?? 'Unassigned' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        {{$shipment->rack_location}}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($shipment->status === 'pending')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Received</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $allShipments->links() }}
    </div>
    @else
    <div class="p-6 text-center text-gray-500">
        No shipments found.
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.shipment-row');
    
    function applyFilter() {
        const value = statusFilter.value;
        
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            
            if (value === 'all' || status === value) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    if (statusFilter && rows.length > 0) {
        statusFilter.addEventListener('change', applyFilter);
    }
});
</script>
@endsection
