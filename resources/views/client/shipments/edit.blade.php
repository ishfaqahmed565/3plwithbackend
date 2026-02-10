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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input type="text" name="product_name" value="{{ old('product_name', $shipment->product_name) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                <input type="number" name="quantity_total" value="{{ old('quantity_total', $shipment->quantity_total) }}" required min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tracking ID *</label>
                <input type="text" name="tracking_id" value="{{ old('tracking_id', $shipment->tracking_id) }}" required
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

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Add Images / PDF Documents</label>
                <input type="file" name="attachments[]" id="client-attachments" multiple accept="image/*,application/pdf"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Upload multiple images or PDFs (Max: 5MB each)</p>

                <div id="client-attachments-preview" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            </div>
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
@endsection
