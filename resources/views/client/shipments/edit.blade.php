@extends('layouts.app')

@section('title', 'Edit Shipment')

@php
    $color = 'green';
    $title = 'Edit Shipment';
    $userName = auth('client')->user()->name;
    $logoutRoute = route('client.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('client.dashboard'), 'active' => false],
        ['label' => 'Shipments', 'url' => route('client.shipments.index'), 'active' => true],
    ];
@endphp

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Edit Shipment</h2>
        <a href="{{ route('client.shipments.show', $shipment) }}" class="text-green-600 hover:text-green-800">← Back to Details</a>
    </div>

    <form method="POST" action="{{ route('client.shipments.update', $shipment) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-lg font-semibold text-gray-800 mb-4">Products *</label>
                <div id="products-list" class="space-y-4">
                    @php $oldProducts = old('products', []); @endphp
                    @if(!empty($oldProducts))
                        @foreach($oldProducts as $i => $p)
                            <div class="product-row bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 sm:col-span-5">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Product Name</label>
                                        <input type="text" name="products[{{ $i }}][name]" value="{{ $p['name'] ?? '' }}" placeholder="Enter product name" required
                                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    </div>
                                    <div class="col-span-12 sm:col-span-5">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Description</label>
                                        <input type="text" name="products[{{ $i }}][description]" value="{{ $p['description'] ?? '' }}" placeholder="Product description (optional)"
                                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    </div>
                                    <div class="col-span-12 sm:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Quantity</label>
                                        <input type="number" name="products[{{ $i }}][quantity]" min="1" required value="{{ $p['quantity'] ?? 1 }}"
                                               oninvalid="this.setCustomValidity('Please enter a quantity of at least 1')"
                                               oninput="this.setCustomValidity('')"
                                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($shipment->products as $i => $prod)
                            <div class="product-row bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 sm:col-span-5">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Product Name</label>
                                        <input type="text" name="products[{{ $i }}][name]" value="{{ $prod->name }}" placeholder="Enter product name" required
                                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    </div>
                                    <div class="col-span-12 sm:col-span-5">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Description</label>
                                        <input type="text" name="products[{{ $i }}][description]" value="{{ $prod->description }}" placeholder="Product description (optional)"
                                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    </div>
                                    <div class="col-span-12 sm:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Quantity</label>
                                        <input type="number" name="products[{{ $i }}][quantity]" min="1" required value="{{ $prod->quantity_expected }}"
                                               oninvalid="this.setCustomValidity('Please enter a quantity of at least 1')"
                                               oninput="this.setCustomValidity('')"
                                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="mt-4">
                    <button type="button" id="add-product" 
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Another Product
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Source *</label>
                <input type="text" name="source" value="{{ old('source', $shipment->source) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Description</label>
                <textarea name="product_description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('product_description', $shipment->product_description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" name="category" value="{{ old('category', $shipment->category) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tracking ID (Optional)</label>
                <input type="text" name="tracking_id" value="{{ old('tracking_id', $shipment->tracking_id) }}"
                       placeholder="e.g., 1Z999AA10123456784"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('tracking_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Partner *</label>
                <select name="delivery_partner" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Select delivery partner</option>
                    <option value="FEDEX" {{ old('delivery_partner', $shipment->delivery_partner) === 'FEDEX' ? 'selected' : '' }}>FedEx</option>
                    <option value="UPS" {{ old('delivery_partner', $shipment->delivery_partner) === 'UPS' ? 'selected' : '' }}>UPS</option>
                    <option value="AMAZON" {{ old('delivery_partner', $shipment->delivery_partner) === 'AMAZON' ? 'selected' : '' }}>Amazon Logistics</option>
                    <option value="USPS" {{ old('delivery_partner', $shipment->delivery_partner) === 'USPS' ? 'selected' : '' }}>USPS</option>
                    <option value="DHL" {{ old('delivery_partner', $shipment->delivery_partner) === 'DHL' ? 'selected' : '' }}>DHL</option>
                    <option value="Other" {{ old('delivery_partner', $shipment->delivery_partner) === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('delivery_partner')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

<x-file-upload-modal 
                inputName="attachments"
                inputId="client-attachments-edit"
                previewId="client-attachments-preview-edit"
                modalId="upload-modal-edit"
                color="green"
                label="Add Images / PDF Documents"
                :required="false"
            />
        </div>

        @if($shipment->attachments->where('context', 'client_upload')->count())
        <div class="mt-8">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Existing Attachments</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($shipment->attachments->where('context', 'client_upload') as $attachment)
                    <div class="border rounded-lg p-3 bg-gray-50 relative" data-attachment-id="{{ $attachment->id }}">
                        <button type="button" class="absolute top-2 right-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded remove-existing-attachment">Remove</button>
                        <p class="text-sm font-medium text-gray-700 mb-2 break-all">{{ $attachment->original_name }}</p>
                        @if(str_starts_with($attachment->mime_type, 'image/'))
                            <img src="{{ Storage::url($attachment->file_path) }}" alt="Attachment" class="w-full h-40 object-cover rounded">
                        @else
                            <div class="flex items-center justify-center h-40 bg-white border border-dashed rounded text-sm text-gray-600">PDF Document</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div id="removed-attachments"></div>

        <div class="mt-6 flex space-x-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                Update Shipment
            </button>
            <a href="{{ route('client.shipments.show', $shipment) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    (function () {
        const input = document.getElementById('client-attachments');
        const preview = document.getElementById('client-attachments-preview');
        const removedContainer = document.getElementById('removed-attachments');

        if (input && preview) {
            const renderPreview = (files) => {
                preview.innerHTML = '';

                files.forEach((file, index) => {
                    const card = document.createElement('div');
                    card.className = 'border rounded-lg p-3 bg-gray-50 relative';

                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'absolute top-2 right-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded';
                    removeButton.textContent = 'Remove';
                    removeButton.addEventListener('click', () => {
                        const dt = new DataTransfer();
                        files.filter((_, i) => i !== index).forEach((f) => dt.items.add(f));
                        input.files = dt.files;
                        renderPreview(Array.from(input.files));
                    });

                    const title = document.createElement('p');
                    title.className = 'text-sm font-medium text-gray-700 mb-2 break-all';
                    title.textContent = file.name;

                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.className = 'w-full h-40 object-cover rounded';
                        img.src = URL.createObjectURL(file);
                        img.onload = () => URL.revokeObjectURL(img.src);
                        card.appendChild(img);
                    } else {
                        const pdfBadge = document.createElement('div');
                        pdfBadge.className = 'flex items-center justify-center h-40 bg-white border border-dashed rounded text-sm text-gray-600';
                        pdfBadge.textContent = 'PDF Document';
                        card.appendChild(pdfBadge);
                    }

                    card.appendChild(title);
                    card.appendChild(removeButton);
                    preview.appendChild(card);
                });
            };

            input.addEventListener('change', () => {
                renderPreview(Array.from(input.files));
            });
        }

        document.querySelectorAll('.remove-existing-attachment').forEach((button) => {
            button.addEventListener('click', () => {
                const card = button.closest('[data-attachment-id]');
                if (!card) {
                    return;
                }
                const id = card.getAttribute('data-attachment-id');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_attachments[]';
                input.value = id;
                removedContainer.appendChild(input);
                card.remove();
            });
        });
    })();
</script>
    <script>
        (function () {
            const list = document.getElementById('products-list');
            const addBtn = document.getElementById('add-product');

            if (!list || !addBtn) return;

            function reindex() {
                const rows = Array.from(list.querySelectorAll('.product-row'));
                rows.forEach((row, idx) => {
                    const name = row.querySelector('input[name$="[name]"]');
                    const desc = row.querySelector('input[name$="[description]"]');
                    const qty = row.querySelector('input[name$="[quantity]"]');

                    if (name) name.name = `products[${idx}][name]`;
                    if (desc) desc.name = `products[${idx}][description]`;
                    if (qty) qty.name = `products[${idx}][quantity]`;

                    // ensure remove button
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
                            reindex();
                        });
                        row.appendChild(remove);
                    } else if (remove && rows.length === 1) {
                        remove.remove();
                    }
                });
            }

            addBtn.addEventListener('click', () => {
                const idx = list.querySelectorAll('.product-row').length;
                const div = document.createElement('div');
                div.className = 'product-row bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200';
                div.innerHTML = `
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Product Name</label>
                            <input type="text" name="products[${idx}][name]" placeholder="Enter product name" required
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        </div>
                        <div class="col-span-12 sm:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Description</label>
                            <input type="text" name="products[${idx}][description]" placeholder="Product description (optional)"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        </div>
                        <div class="col-span-12 sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Quantity</label>
                            <input type="number" name="products[${idx}][quantity]" min="1" required value="1"
                                   oninvalid="this.setCustomValidity('Please enter a quantity of at least 1')"
                                   oninput="this.setCustomValidity('')"
                                   class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        </div>
                    </div>
                `;
                list.appendChild(div);
                reindex();
            });

            reindex();
        })();
    </script>
@endsection
