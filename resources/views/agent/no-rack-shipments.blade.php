@extends('layouts.app')

@section('title', 'No Rack Assignment')

@php
    $color = 'purple';
    $title = 'No Rack Assignment';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    // $navigation is now provided by AgentNavigationComposer
@endphp

@section('content')
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900">Received Shipments - No Rack Assignment</h2>
        <p class="text-sm text-gray-600 mt-1">These shipments have been received but need rack location assignment</p>
    </div>
    @if($noRackShipments->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipment Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tracking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Group ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($noRackShipments as $shipment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-mono font-semibold text-purple-600">{{ $shipment->shipment_code }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($shipment->tracking_id)
                        <span class="font-mono text-gray-900">{{ $shipment->tracking_id }}</span>
                        @else
                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">No Tracking ID</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $shipment->product_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->client->name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->client->group_id ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $shipment->receivedByAgent?->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($shipment->warehouse_name)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                            {{ $shipment->warehouse_name }}
                        </span>
                        @else
                        <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->scan1_at ? $shipment->scan1_at->format('M d, Y') : 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <button onclick="openRackModal({{ $shipment->id }}, '{{ $shipment->shipment_code }}')" 
                                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs font-semibold">
                            Assign Rack
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $noRackShipments->links() }}
    </div>
    @else
    <div class="p-6 text-center text-gray-500">
        All received shipments have rack locations assigned.
    </div>
    @endif
</div>

<!-- Rack Assignment Modal -->
<div id="rackModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Assign Rack Location</h3>
            <p class="text-sm text-gray-600 mb-4">Shipment: <span id="modalShipmentCode" class="font-mono font-semibold"></span></p>
            <form id="rackForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="rack_location_input" class="block text-sm font-medium text-gray-700 mb-2">Rack Location *</label>
                    <input type="text" id="rack_location_input" name="rack_location" required
                           placeholder="e.g., A1-05"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRackModal()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRackModal(shipmentId, shipmentCode) {
    document.getElementById('modalShipmentCode').textContent = shipmentCode;
    document.getElementById('rackForm').action = '/agent/shipments/' + shipmentId + '/assign-rack';
    document.getElementById('rack_location_input').value = '';
    document.getElementById('rackModal').classList.remove('hidden');
}

function closeRackModal() {
    document.getElementById('rackModal').classList.add('hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRackModal();
    }
});
</script>
@endsection
