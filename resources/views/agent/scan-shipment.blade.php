@extends('layouts.app')

@section('title', 'Scan-1: Receive Shipment')

@php
    $color = 'purple';
    $title = 'Agent Dashboard';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('agent.dashboard'), 'active' => false],
        ['label' => 'Scan-1 (Shipment)', 'url' => route('agent.scan.shipment'), 'active' => true],
    ];
@endphp

@section('content')
<div class="max-w-4xl mx-auto">
    @if(!isset($shipment))
    <!-- Lookup Form -->
    <div class="bg-white rounded-lg shadow p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-purple-600 mb-2">Scan-1: Receive Shipment</h2>
            <p class="text-gray-600">Enter tracking ID to lookup shipment</p>
        </div>
        
        <form method="GET" action="{{ route('agent.scan.shipment') }}">
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tracking ID *</label>
                    <input type="text" name="tracking_id" autofocus required
                           placeholder="Enter tracking number..."
                           value="{{ request('tracking_id') }}"
                           class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold">
                        Lookup Shipment
                    </button>
                </div>
            </div>
        </form>
    </div>
    @else
    <!-- Shipment Details & Verification Form -->
    <div class="bg-white rounded-lg shadow p-8 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Shipment Details</h2>
            <a href="{{ route('agent.shipments.show', $shipment->id) }}" target="_blank" 
               class="bg-purple-100 hover:bg-purple-200 text-purple-700 px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View Full Details
            </a>
        </div>
        
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-600">Shipment ID</p>
                <p class="text-lg font-semibold text-gray-900">{{ $shipment->shipment_code }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tracking ID</p>
                <p class="text-lg font-mono font-semibold text-purple-600">{{ $shipment->tracking_id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Product Name</p>
                <p class="text-lg font-semibold text-gray-900">{{ $shipment->product_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Expected Quantity</p>
                <p class="text-lg font-semibold text-gray-900">{{ $shipment->quantity_total }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Source</p>
                <p class="text-lg text-gray-900">{{ $shipment->source }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Delivery Partner</p>
                <p class="text-lg font-semibold text-gray-900">{{ $shipment->delivery_partner }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Client</p>
                <p class="text-lg text-gray-900">{{ $shipment->client->name }} (Group ID: {{ $shipment->client->group_id }})</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Current Status</p>
                <span class="inline-block px-3 py-1 rounded text-sm font-semibold bg-yellow-100 text-yellow-800">
                    {{ ucfirst($shipment->status) }}
                </span>
            </div>
        </div>
        
        @if($shipment->description)
        <div class="mb-6">
            <p class="text-sm text-gray-600 mb-1">Description</p>
            <p class="text-gray-900">{{ $shipment->description }}</p>
        </div>
        @endif
        
        @if($shipment->product_image_path)
        <div class="mb-6">
            <p class="text-sm text-gray-600 mb-2">Product Image (Uploaded by Client)</p>
            <div class="border-2 border-purple-200 rounded-lg p-2 inline-block">
                <img src="{{ Storage::url($shipment->product_image_path) }}" alt="Product Image" class="max-h-64 rounded">
            </div>
        </div>
        @endif
    </div>
    
    <!-- Verification Form -->
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Verify & Complete Receipt</h2>
        
        <form method="POST" action="{{ route('agent.scan.shipment') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tracking_id" value="{{ $shipment->tracking_id }}">
            
            <div class="mb-6">
                <label for="rack_location" class="block text-sm font-medium text-gray-700 mb-2">Assign Rack Location *</label>
                <input type="text" name="rack_location" id="rack_location" required
                    value="{{ old('rack_location') }}"
                    placeholder="Enter rack location code (e.g., A1-05)"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                @error('rack_location')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Enter the rack location code where this product will be stored</p>
            </div>
            
            <div class="mb-6">
                <label for="received_quantity" class="block text-sm font-medium text-gray-700 mb-2">Verify Received Quantity *</label>
                <input type="number" name="received_quantity" id="received_quantity" required min="1"
                    value="{{ old('received_quantity', $shipment->total_quantity) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                @error('received_quantity')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Confirm the actual quantity received (Expected: {{ $shipment->quantity_total }})</p>
            </div>
            
            <div class="mb-6">
                <label for="product_condition" class="block text-sm font-medium text-gray-700 mb-2">Product Condition *</label>
                <select name="product_condition" id="product_condition" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Select condition</option>
                    <option value="excellent" {{ old('product_condition') === 'excellent' ? 'selected' : '' }}>Excellent</option>
                    <option value="good" {{ old('product_condition') === 'good' ? 'selected' : '' }}>Good</option>
                    <option value="fair" {{ old('product_condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                    <option value="damaged" {{ old('product_condition') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                </select>
                @error('product_condition')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="scan1_notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea name="scan1_notes" id="scan1_notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                    placeholder="Any additional observations or notes...">{{ old('scan1_notes') }}</textarea>
                @error('scan1_notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="scan1_image" class="block text-sm font-medium text-gray-700 mb-2">Proof Image *</label>
                <input type="file" name="scan1_image" id="scan1_image" required accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                @error('scan1_image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Upload photo of received shipment as proof</p>
            </div>
            
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                <p class="text-sm font-semibold text-purple-900 mb-2">Ready to Complete Scan-1</p>
                <p class="text-sm text-purple-800">This will mark the shipment as received and make inventory available for orders.</p>
            </div>
            
            <div class="flex gap-4">
                <a href="{{ route('agent.scan.shipment') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold">
                    Cancel
                </a>
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold">
                    Complete Scan-1
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
