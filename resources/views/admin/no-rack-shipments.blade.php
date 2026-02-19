@extends('layouts.app')

@section('title', 'No Rack Shipments')

@php
    $color = 'blue';
    $title = 'Shipments Without Rack Location';
    $userName = auth('admin')->user()->name;
    $logoutRoute = route('admin.logout');
    // $navigation is now provided by AdminNavigationComposer
@endphp

@section('content')
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900">Shipments Without Rack Location</h2>
        <p class="text-sm text-gray-600 mt-1">Received shipments awaiting rack assignment</p>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($noRackShipments as $shipment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-mono font-semibold text-blue-600">{{ $shipment->shipment_code }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $shipment->tracking_id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $shipment->product_description }}
                        <div class="text-xs text-gray-500">{{ $shipment->category }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $shipment->client?->name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->receivedByAgent?->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->received_in_warehouse ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $shipment->scan1_completed_at ? $shipment->scan1_completed_at->format('M d, Y g:i A') : 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <button onclick="openRackModal({{ $shipment->id }}, '{{ $shipment->shipment_code }}')" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
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
        No shipments without rack location found.
    </div>
    @endif
</div>

<!-- Rack Assignment Modal -->
<div id="rackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Assign Rack Location</h3>
            <p class="text-sm text-gray-600 mt-1">Shipment: <span id="modalShipmentCode" class="font-mono font-semibold"></span></p>
        </div>
        <form id="rackForm" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label for="rack_location" class="block text-sm font-medium text-gray-700 mb-2">Rack Location *</label>
                <input type="text" id="rack_location" name="rack_location" required placeholder="e.g., A1-01" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeRackModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Assign Rack</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRackModal(shipmentId, shipmentCode) {
    const modal = document.getElementById('rackModal');
    const form = document.getElementById('rackForm');
    const codeSpan = document.getElementById('modalShipmentCode');
    
    form.action = `/admin/shipments/${shipmentId}/assign-rack`;
    codeSpan.textContent = shipmentCode;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRackModal() {
    const modal = document.getElementById('rackModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('rack_location').value = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('rackModal');
    if (modal) {
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeRackModal();
            }
        });
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeRackModal();
            }
        });
    }
});
</script>
@endsection
