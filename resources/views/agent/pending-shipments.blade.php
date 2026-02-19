@extends('layouts.app')

@section('title', 'Pending Shipments')

@php
    $color = 'purple';
    $title = 'Pending Shipments';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    // $navigation is now provided by AgentNavigationComposer
@endphp

@section('content')
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Pending Shipments (Scan-1 Required)</h2>
                <p class="text-sm text-gray-600 mt-1">All shipments awaiting warehouse receipt</p>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Filter:</label>
                <select id="trackingFilter" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="all">All Shipments</option>
                    <option value="no-tracking">No Tracking ID</option>
                    <option value="with-tracking">With Tracking ID</option>
                </select>
            </div>
        </div>
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
                <tr class="hover:bg-gray-50 shipment-row" data-has-tracking="{{ $shipment->tracking_id ? 'yes' : 'no' }}">
                    <td class="px-6 py-4 text-sm">
                        @if($shipment->tracking_id)
                        <a href="{{ route('agent.scan.shipment', ['tracking_id' => $shipment->tracking_id]) }}" class="font-mono font-semibold text-purple-600 hover:text-purple-800 hover:underline">
                            {{ $shipment->tracking_id }}
                        </a>
                        @else
                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">No Tracking ID</span>
                        @endif
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
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $pendingShipments->links() }}
    </div>
    @else
    <div class="p-6 text-center text-gray-500">
        No pending shipments. All shipments have been received.
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filter = document.getElementById('trackingFilter');
    const rows = document.querySelectorAll('.shipment-row');
    
    if (filter && rows.length > 0) {
        filter.addEventListener('change', function() {
            const value = this.value;
            rows.forEach(row => {
                const hasTracking = row.getAttribute('data-has-tracking');
                if (value === 'all') {
                    row.style.display = '';
                } else if (value === 'no-tracking' && hasTracking === 'no') {
                    row.style.display = '';
                } else if (value === 'with-tracking' && hasTracking === 'yes') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection
