@extends('layouts.app')

@section('title', 'Unknown Shipments')

@php
    $color = 'purple';
    $title = 'Unknown Shipments';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    // $navigation is now provided by AgentNavigationComposer
@endphp

@section('content')
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Unknown Shipments</h2>
                <p class="text-sm text-gray-600 mt-1">All shipments created by agents/admins (received directly at warehouse)</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('agent.shipments.create-unknown') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-semibold text-sm">
                    + Create Unknown Shipment
                </a>
                <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Client:</label>
                <select id="clientFilter" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="all">All</option>
                    <option value="assigned">With Client</option>
                    <option value="unassigned">No Client</option>
                </select>
                <label class="text-sm text-gray-600 ml-3">Rack:</label>
                <select id="rackFilter" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="all">All</option>
                    <option value="assigned">With Rack</option>
                    <option value="unassigned">No Rack</option>
                </select>
                </div>
            </div>
        </div>
    </div>
    @if($unknownShipments->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipment Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tracking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rack Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($unknownShipments as $shipment)
                <tr class="hover:bg-gray-50 unknown-row" 
                    data-has-client="{{ $shipment->client_id ? 'yes' : 'no' }}"
                    data-has-rack="{{ $shipment->rack_location ? 'yes' : 'no' }}">
                    <td class="px-6 py-4 text-sm font-mono font-semibold text-purple-600">{{ $shipment->shipment_code }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $shipment->tracking_id }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($shipment->client_id)
                        <span class="text-gray-900">{{ $shipment->client->name }}</span>
                        @else
                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($shipment->rack_location)
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">{{ $shipment->rack_location }}</span>
                        @else
                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">No Rack</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if($shipment->created_by_agent_id)
                        <span class="text-purple-600">Agent: {{ $shipment->createdByAgent->name }}</span>
                        @else
                        <span class="text-blue-600">Admin: {{ $shipment->createdByAdmin->name }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $shipment->receivedByAgent?->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($shipment->status === 'pending')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Received</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('agent.shipments.edit-unknown', $shipment->id) }}" class="text-purple-600 hover:text-purple-800 font-semibold">
                            Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $unknownShipments->links() }}
    </div>
    @else
    <div class="p-6 text-center text-gray-500">
        No unknown shipments found.
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientFilter = document.getElementById('clientFilter');
    const rackFilter = document.getElementById('rackFilter');
    const rows = document.querySelectorAll('.unknown-row');
    
    function applyFilters() {
        const clientValue = clientFilter.value;
        const rackValue = rackFilter.value;
        
        rows.forEach(row => {
            const hasClient = row.getAttribute('data-has-client');
            const hasRack = row.getAttribute('data-has-rack');
            
            let showRow = true;
            
            // Apply client filter
            if (clientValue === 'assigned' && hasClient !== 'yes') {
                showRow = false;
            } else if (clientValue === 'unassigned' && hasClient !== 'no') {
                showRow = false;
            }
            
            // Apply rack filter
            if (rackValue === 'assigned' && hasRack !== 'yes') {
                showRow = false;
            } else if (rackValue === 'unassigned' && hasRack !== 'no') {
                showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    if (clientFilter && rackFilter && rows.length > 0) {
        clientFilter.addEventListener('change', applyFilters);
        rackFilter.addEventListener('change', applyFilters);
    }
});
</script>
@endsection
