@extends('layouts.app')

@section('title', 'Scan-1: Receive Shipment')

@php
    $color = 'purple';
    $title = 'Agent Dashboard';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    // $navigation is now provided by AgentNavigationComposer
@endphp

@section('content')
<div class="max-w-4xl mx-auto">
    @if(!isset($shipment) && !isset($trackingId))
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
    @elseif(isset($trackingId))
    <!-- Not Found - Show Modal -->
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
                           value="{{ $trackingId }}"
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
    
    <!-- Confirmation Modal -->
    <div id="notFoundModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-yellow-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Shipment Not Found</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    There is no shipment with tracking ID <span class="font-mono font-semibold text-purple-600">{{ $trackingId }}</span> in the system. Would you like to create an unknown shipment?
                </p>
                
                <div class="flex gap-3">
                    <a href="{{ route('agent.scan.shipment') }}" 
                       class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                        No, Go Back
                    </a>
                    <a href="{{ route('agent.shipments.create-unknown', ['tracking_id' => $trackingId]) }}" 
                       class="flex-1 text-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        Yes, Create
                    </a>
                </div>
            </div>
        </div>
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
        
        <!-- Products List -->
        @if($shipment->products->count() > 0)
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Products in this Shipment</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($shipment->products as $product)
                <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-200 rounded-xl p-5 shadow-sm">
                    <div class="flex items-start justify-between mb-3">
                        <h4 class="text-base font-bold text-gray-900 flex-1">{{ $product->name }}</h4>
                        <span class="ml-2 px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-bold">
                            {{ $product->quantity_expected }}
                        </span>
                    </div>
                    
                    @if($product->description)
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>
                    @endif
                    
                    <div class="pt-3 border-t border-purple-200">
                        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide">Expected Quantity</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $product->quantity_expected }}</p>
                    </div>
                </div>
                @endforeach
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
                <label for="rack_location" class="block text-sm font-medium text-gray-700 mb-2">Assign Rack Location (Optional)</label>
                <input type="text" name="rack_location" id="rack_location"
                    value="{{ old('rack_location') }}"
                    placeholder="Enter rack location code (e.g., A1-05) or leave blank to assign later"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                @error('rack_location')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">You can assign the rack location now or later from the dashboard</p>
            </div>
            
            <!-- Per-Product Verification -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Verify Each Product *</h3>
                <div class="space-y-6">
                    @foreach($shipment->products as $index => $product)
                    <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-200 rounded-xl p-5">
                        <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product->id }}">
                        
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-900">{{ $product->name }}</h4>
                                @if($product->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $product->description }}</p>
                                @endif
                            </div>
                            <span class="ml-3 px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-bold">
                                Expected: {{ $product->quantity_expected }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Received Quantity *</label>
                                <input type="number" name="products[{{ $index }}][quantity_received]" required min="1" max="{{ $product->quantity_expected }}"
                                    value="{{ old('products.'.$index.'.quantity_received', $product->quantity_expected) }}"
                                    class="w-full px-4 py-2 border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 font-semibold text-center">
                                @error('products.'.$index.'.quantity_received')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Condition *</label>
                                <select name="products[{{ $index }}][condition]" required
                                    class="w-full px-4 py-2 border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                    <option value="">Select</option>
                                    <option value="excellent" {{ old('products.'.$index.'.condition') === 'excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="good" {{ old('products.'.$index.'.condition') === 'good' ? 'selected' : '' }}>Good</option>
                                    <option value="fair" {{ old('products.'.$index.'.condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                                    <option value="damaged" {{ old('products.'.$index.'.condition') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                                </select>
                                @error('products.'.$index.'.condition')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                                <textarea name="products[{{ $index }}][notes]" rows="2" placeholder="Optional notes about this product..."
                                    class="w-full px-4 py-2 border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('products.'.$index.'.notes') }}</textarea>
                                @error('products.'.$index.'.notes')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mb-6">
                <label for="scan1_notes" class="block text-sm font-medium text-gray-700 mb-2">General Shipment Notes</label>
                <textarea name="scan1_notes" id="scan1_notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                    placeholder="Any general observations about the entire shipment...">{{ old('scan1_notes') }}</textarea>
                @error('scan1_notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <x-file-upload-modal 
                inputName="scan1_files"
                inputId="scan1_files"
                previewId="scan1-files-preview"
                modalId="upload-modal-scan"
                color="purple"
                label="Proof Files"
                :required="true"
            />

            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Line Items (Barcodes)</label>
                    <button type="button" id="add-line-item" class="text-sm bg-purple-100 text-purple-700 px-3 py-1 rounded">+ Add Item</button>
                </div>
                <div id="line-items-container" class="space-y-3"></div>
                <p class="text-sm text-gray-500 mt-1">Scan or enter barcode values to create line items.</p>
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

<script>
    (function () {
        const addLineItemButton = document.getElementById('add-line-item');
        const lineItemsContainer = document.getElementById('line-items-container');

        const addLineItemRow = (value = '') => {
            if (!lineItemsContainer) {
                return;
            }

            const row = document.createElement('div');
            row.className = 'flex items-center gap-3';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'line_items[]';
            input.value = value;
            input.placeholder = 'Scan barcode...';
            input.className = 'flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'text-sm bg-red-100 text-red-700 px-3 py-2 rounded';
            removeButton.textContent = 'Remove';
            removeButton.addEventListener('click', () => {
                row.remove();
            });

            row.appendChild(input);
            row.appendChild(removeButton);
            lineItemsContainer.appendChild(row);
        };

        if (addLineItemButton && lineItemsContainer) {
            addLineItemButton.addEventListener('click', () => addLineItemRow());
            addLineItemRow();
        }
    })();
</script>
@endsection
