@extends('layouts.app')

@section('title', 'Create Unknown Shipment')

@php
    $color = 'purple';
    $title = 'Create Unknown Shipment';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    // $navigation is now provided by AgentNavigationComposer
@endphp

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Unknown Shipment</h2>
    
    <div class="mb-4 p-4 bg-purple-50 border border-purple-200 rounded-lg">
        <p class="text-sm text-purple-800">
            <strong>Note:</strong> Use this form to create and immediately receive unknown shipments. You can optionally assign a client now or leave it unassigned for later assignment.
        </p>
    </div>
    
    <form method="POST" action="{{ route('agent.shipments.store-unknown') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Assign to Client (Optional)</label>
                <div class="relative">
                    <input type="text" id="client-search" placeholder="Search by client name or group ID..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <input type="hidden" name="client_id" id="client-id-input" value="{{ old('client_id') }}">
                    <div id="client-dropdown" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-2 hover:bg-purple-50 cursor-pointer client-option" data-id="" data-name="-- Unassigned --">
                            <div class="font-medium text-gray-700">-- Unassigned --</div>
                        </div>
                        @foreach($clients as $client)
                        <div class="p-2 hover:bg-purple-50 cursor-pointer client-option border-t" 
                            data-id="{{ $client->id }}" 
                            data-name="{{ $client->name }}" 
                            data-group="{{ $client->group_id }}">
                            <div class="font-medium text-gray-900">{{ $client->name }}</div>
                            <div class="text-xs text-gray-500">Group ID: {{ $client->group_id }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-1">Leave unassigned if client information is not available</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tracking ID *</label>
                <input type="text" name="tracking_id" value="{{ old('tracking_id', request('tracking_id')) }}" required
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
                    <option value="FEDEX" {{ old('delivery_partner') === 'FEDEX' ? 'selected' : '' }}>FedEx</option>
                    <option value="UPS" {{ old('delivery_partner') === 'UPS' ? 'selected' : '' }}>UPS</option>
                    <option value="AMAZON" {{ old('delivery_partner') === 'AMAZON' ? 'selected' : '' }}>Amazon Logistics</option>
                    <option value="USPS" {{ old('delivery_partner') === 'USPS' ? 'selected' : '' }}>USPS</option>
                    <option value="DHL" {{ old('delivery_partner') === 'DHL' ? 'selected' : '' }}>DHL</option>
                    <option value="Other" {{ old('delivery_partner') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Source *</label>
                <input type="text" name="source" value="{{ old('source') }}" required
                       placeholder="e.g., Amazon, eBay, Website"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" name="category" value="{{ old('category') }}"
                       placeholder="e.g., Electronics, Clothing"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Description</label>
                <textarea name="product_description" rows="3"
                          placeholder="Brief description of the products..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('product_description') }}</textarea>
            </div>
        </div>
        
        <hr class="my-8 border-t-2 border-purple-200">
        
        <h2 class="text-xl font-bold text-gray-900 mb-6">Verify & Complete Receipt</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
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
        </div>
        
        <div class="mb-6">
            <label class="block text-lg font-semibold text-gray-800 mb-4">Products in Shipment</label>
            <div id="products-list" class="space-y-4">
                <div class="product-row bg-gradient-to-br from-purple-50 to-white border-2 border-purple-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Product Name *</label>
                            <input type="text" name="products[0][name]" placeholder="Enter product name" required value="{{ old('products.0.name') }}"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        </div>
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Description</label>
                            <input type="text" name="products[0][description]" placeholder="Product description (optional)" value="{{ old('products.0.description') }}"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        </div>
                        <div class="col-span-12 sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Quantity *</label>
                            <input type="number" name="products[0][quantity]" min="1" required value="{{ old('products.0.quantity', 1) }}"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 font-semibold text-center">
                        </div>
                        <div class="col-span-12 sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Type of Sale</label>
                            <select name="products[0][type_of_sale]"
                                    class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                <option value="">Select</option>
                                <option value="FBA" {{ old('products.0.type_of_sale') === 'FBA' ? 'selected' : '' }}>FBA</option>
                                <option value="FBM" {{ old('products.0.type_of_sale') === 'FBM' ? 'selected' : '' }}>FBM</option>
                                <option value="WFS" {{ old('products.0.type_of_sale') === 'WFS' ? 'selected' : '' }}>WFS</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Product Image</label>
                            <input type="file" name="products[0][image]" accept="image/*"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 text-sm">
                        </div>
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Link URL</label>
                            <input type="url" name="products[0][link_url]" placeholder="https://..." value="{{ old('products.0.link_url') }}"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        </div>
                    </div>
                </div>
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
            <p class="text-sm font-semibold text-purple-900 mb-2">Ready to Create & Receive Shipment</p>
            <p class="text-sm text-purple-800">This will create the shipment and mark it as received in the warehouse.</p>
        </div>
        
        <div class="mt-8 flex gap-4">
            <a href="{{ route('agent.dashboard') }}"
               class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                Create & Receive Shipment
            </button>
        </div>
    </form>
</div>

<script>
    (function () {
        // Searchable client select
        const clientSearch = document.getElementById('client-search');
        const clientIdInput = document.getElementById('client-id-input');
        const clientDropdown = document.getElementById('client-dropdown');
        const clientOptions = document.querySelectorAll('.client-option');

        if (clientSearch && clientDropdown) {
            // Show dropdown on focus
            clientSearch.addEventListener('focus', () => {
                clientDropdown.classList.remove('hidden');
            });

            // Filter options as user types
            clientSearch.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                clientDropdown.classList.remove('hidden');
                clientOptions.forEach(option => {
                    const name = option.dataset.name?.toLowerCase() || '';
                    const group = option.dataset.group?.toLowerCase() || '';
                    if (name.includes(searchTerm) || group.includes(searchTerm)) {
                        option.classList.remove('hidden');
                    } else {
                        option.classList.add('hidden');
                    }
                });
            });

            // Handle option selection
            clientOptions.forEach(option => {
                option.addEventListener('click', () => {
                    clientIdInput.value = option.dataset.id;
                    clientSearch.value = option.dataset.name;
                    clientDropdown.classList.add('hidden');
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!clientSearch.contains(e.target) && !clientDropdown.contains(e.target)) {
                    clientDropdown.classList.add('hidden');
                }
            });

            // Set initial value if exists
            const initialClientId = clientIdInput.value;
            if (initialClientId) {
                const selectedOption = Array.from(clientOptions).find(opt => opt.dataset.id === initialClientId);
                if (selectedOption) {
                    clientSearch.value = selectedOption.dataset.name;
                }
            }
        }

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
                    const typeOfSale = row.querySelector('select[name$="[type_of_sale]"]');
                    const image = row.querySelector('input[name$="[image]"]');
                    const linkUrl = row.querySelector('input[name$="[link_url]"]');

                    if (name) name.name = `products[${idx}][name]`;
                    if (desc) desc.name = `products[${idx}][description]`;
                    if (qty) qty.name = `products[${idx}][quantity]`;
                    if (typeOfSale) typeOfSale.name = `products[${idx}][type_of_sale]`;
                    if (image) image.name = `products[${idx}][image]`;
                    if (linkUrl) linkUrl.name = `products[${idx}][link_url]`;

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
                        <div class="col-span-12 sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Type of Sale</label>
                            <select name="products[${idx}][type_of_sale]"
                                    class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                <option value="">Select</option>
                                <option value="FBA">FBA</option>
                                <option value="FBM">FBM</option>
                                <option value="WFS">WFS</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Product Image</label>
                            <input type="file" name="products[${idx}][image]" accept="image/*"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 text-sm">
                        </div>
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Link URL</label>
                            <input type="url" name="products[${idx}][link_url]" placeholder="https://..."
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
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
