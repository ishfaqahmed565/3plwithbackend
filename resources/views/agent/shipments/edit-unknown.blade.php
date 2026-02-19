@extends('layouts.app')

@section('title', 'Edit Unknown Shipment')

@php
    $color = 'purple';
    $title = 'Edit Unknown Shipment';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    // $navigation is now provided by AgentNavigationComposer
@endphp

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Edit Unknown Shipment</h2>
        <span class="text-sm text-gray-600 font-mono bg-gray-100 px-3 py-1 rounded">{{ $shipment->shipment_code }}</span>
    </div>
    
    <div class="mb-4 p-4 bg-purple-50 border border-purple-200 rounded-lg">
        <p class="text-sm text-purple-800">
            <strong>Note:</strong> Update shipment information. Changes will be reflected immediately.
        </p>
    </div>
    
    <form method="POST" action="{{ route('agent.shipments.update-unknown', $shipment->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Assign to Client (Optional)</label>
                <select name="client_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">-- Unassigned --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (old('client_id', $shipment->client_id) == $client->id) ? 'selected' : '' }}>
                            {{ $client->name }} ({{ $client->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Leave unassigned if client information is not available</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tracking ID *</label>
                <input type="text" name="tracking_id" value="{{ old('tracking_id', $shipment->tracking_id) }}" required
                       placeholder="e.g., 1Z999AA10123456784"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                @error('tracking_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Partner *</label>
                <select name="delivery_partner" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Select delivery partner</option>
                    <option value="FEDEX" {{ old('delivery_partner', $shipment->delivery_partner) === 'FEDEX' ? 'selected' : '' }}>FedEx</option>
                    <option value="UPS" {{ old('delivery_partner', $shipment->delivery_partner) === 'UPS' ? 'selected' : '' }}>UPS</option>
                    <option value="AMAZON" {{ old('delivery_partner', $shipment->delivery_partner) === 'AMAZON' ? 'selected' : '' }}>Amazon Logistics</option>
                    <option value="USPS" {{ old('delivery_partner', $shipment->delivery_partner) === 'USPS' ? 'selected' : '' }}>USPS</option>
                    <option value="DHL" {{ old('delivery_partner', $shipment->delivery_partner) === 'DHL' ? 'selected' : '' }}>DHL</option>
                    <option value="Other" {{ old('delivery_partner', $shipment->delivery_partner) === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Source *</label>
                <input type="text" name="source" value="{{ old('source', $shipment->source) }}" required
                       placeholder="e.g., Amazon, eBay, Website"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" name="category" value="{{ old('category', $shipment->category) }}"
                       placeholder="e.g., Electronics, Clothing"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Description</label>
                <textarea name="product_description" rows="3"
                          placeholder="Brief description of the products..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('product_description', $shipment->product_description) }}</textarea>
            </div>
        </div>
        
        <hr class="my-8 border-t-2 border-purple-200">
        
        <h2 class="text-xl font-bold text-gray-900 mb-6">Shipment Details</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="rack_location" class="block text-sm font-medium text-gray-700 mb-2">Rack Location</label>
                <input type="text" name="rack_location" id="rack_location"
                    value="{{ old('rack_location', $shipment->rack_location) }}"
                    placeholder="Enter rack location code (e.g., A1-05)"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                @error('rack_location')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-lg font-semibold text-gray-800 mb-4">Products in Shipment</label>
            <div id="products-list" class="space-y-4">
                @foreach($shipment->products as $index => $product)
                <div class="product-row bg-gradient-to-br from-purple-50 to-white border-2 border-purple-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Product Name *</label>
                            <input type="text" name="products[{{ $index }}][name]" placeholder="Enter product name" required value="{{ old('products.'.$index.'.name', $product->name) }}"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        </div>
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Description</label>
                            <input type="text" name="products[{{ $index }}][description]" placeholder="Product description (optional)" value="{{ old('products.'.$index.'.description', $product->description) }}"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        </div>
                        <div class="col-span-12 sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Quantity *</label>
                            <input type="number" name="products[{{ $index }}][quantity]" min="1" required value="{{ old('products.'.$index.'.quantity', $product->quantity_expected) }}"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 font-semibold text-center">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <button type="button" id="add-product" 
                        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Another Product
                </button>
            </div>
        </div>
        
        <div class="mb-6">
            <label for="scan1_notes" class="block text-sm font-medium text-gray-700 mb-2">General Shipment Notes</label>
            <textarea name="scan1_notes" id="scan1_notes" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                placeholder="Any general observations about the entire shipment...">{{ old('scan1_notes', $shipment->scan1_notes) }}</textarea>
            @error('scan1_notes')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        @if($shipment->attachments->count() > 0)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Existing Attachments</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($shipment->attachments as $attachment)
                <div class="border rounded-lg p-2">
                    @if(str_starts_with($attachment->mime_type, 'image/'))
                        <img src="{{ Storage::url($attachment->file_path) }}" alt="Attachment" class="w-full h-24 object-cover rounded">
                    @else
                        <div class="w-full h-24 bg-gray-100 rounded flex items-center justify-center">
                            <span class="text-xs text-gray-600">{{ pathinfo($attachment->original_name, PATHINFO_EXTENSION) }}</span>
                        </div>
                    @endif
                    <p class="text-xs text-gray-600 mt-1 truncate">{{ $attachment->original_name }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <x-file-upload-modal 
            inputName="scan1_files"
            inputId="scan1_files"
            previewId="scan1-files-preview"
            modalId="upload-modal-scan"
            color="purple"
            label="Add More Proof Files (Optional)"
            :required="false"
        />

        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-gray-700">Line Items (Barcodes)</label>
                <button type="button" id="add-line-item" class="text-sm bg-purple-100 text-purple-700 px-3 py-1 rounded">+ Add Item</button>
            </div>
            <div id="line-items-container" class="space-y-3">
                @foreach($shipment->lineItems as $lineItem)
                <div class="flex items-center gap-3">
                    <input type="text" name="line_items[]" value="{{ $lineItem->barcode_value }}" placeholder="Scan barcode..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <button type="button" class="remove-line-item text-sm bg-red-100 text-red-700 px-3 py-2 rounded">Remove</button>
                </div>
                @endforeach
            </div>
            <p class="text-sm text-gray-500 mt-1">Scan or enter barcode values to create line items.</p>
        </div>
        
        <div class="mt-8 flex gap-4">
            <a href="{{ route('agent.unknown-shipments') }}"
               class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                Update Shipment
            </button>
        </div>
    </form>
</div>

<script>
    (function () {
        // Product list management
        const productsList = document.getElementById('products-list');
        const addProductBtn = document.getElementById('add-product');

        if (productsList && addProductBtn) {
            function reindexProducts() {
                const rows = Array.from(productsList.querySelectorAll('.product-row'));
                rows.forEach((row, idx) => {
                    const name = row.querySelector('input[name$="[name]"]');
                    const desc = row.querySelector('input[name$="[description]"]');
                    const qty = row.querySelector('input[name$="[quantity]"]');

                    if (name) name.name = `products[${idx}][name]`;
                    if (desc) desc.name = `products[${idx}][description]`;
                    if (qty) qty.name = `products[${idx}][quantity]`;

                    let remove = row.querySelector('.remove-product');
                    if (!remove && rows.length > 1) {
                        remove = document.createElement('button');
                        remove.type = 'button';
                        remove.className = 'remove-product mt-3 inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 border-2 border-red-200 hover:border-red-300';
                        remove.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Remove Product
                        `;
                        remove.addEventListener('click', () => {
                            row.remove();
                            reindexProducts();
                        });
                        row.appendChild(remove);
                    } else if (remove && rows.length === 1) {
                        remove.remove();
                    }
                });
            }

            addProductBtn.addEventListener('click', () => {
                const idx = productsList.querySelectorAll('.product-row').length;
                const div = document.createElement('div');
                div.className = 'product-row bg-gradient-to-br from-purple-50 to-white border-2 border-purple-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200';
                div.innerHTML = `
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Product Name *</label>
                            <input type="text" name="products[${idx}][name]" placeholder="Enter product name" required
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        </div>
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Description</label>
                            <input type="text" name="products[${idx}][description]" placeholder="Product description (optional)"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        </div>
                        <div class="col-span-12 sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Quantity *</label>
                            <input type="number" name="products[${idx}][quantity]" min="1" required value="1"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 font-semibold text-center">
                        </div>
                    </div>
                `;
                productsList.appendChild(div);
                reindexProducts();
            });

            reindexProducts();
        }

        // Line items management
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
            removeButton.className = 'remove-line-item text-sm bg-red-100 text-red-700 px-3 py-2 rounded';
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
            
            // Add event listeners to existing remove buttons
            document.querySelectorAll('.remove-line-item').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.target.closest('.flex').remove();
                });
            });
            
            // Add one empty row if no line items exist
            if (lineItemsContainer.children.length === 0) {
                addLineItemRow();
            }
        }
    })();
</script>
@endsection
