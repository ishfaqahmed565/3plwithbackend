@extends('layouts.app')

@section('title', 'Create Shipment')

@php
    $color = 'green';
    $title = 'Create Shipment';
    $userName = auth('client')->user()->name;
    $logoutRoute = route('client.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('client.dashboard'), 'active' => false],
        ['label' => 'Shipments', 'url' => route('client.shipments.index'), 'active' => true],
    ];
@endphp

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New Shipment</h2>
    
    <form method="POST" action="{{ route('client.shipments.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input type="text" name="product_name" value="{{ old('product_name') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Source *</label>
                <input type="text" name="source" value="{{ old('source') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Description</label>
                <textarea name="product_description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('product_description') }}</textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" name="category" value="{{ old('category') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                <input type="number" name="quantity_total" value="{{ old('quantity_total') }}" required min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tracking ID *</label>
                <input type="text" name="tracking_id" value="{{ old('tracking_id') }}" required
                       placeholder="e.g., 1Z999AA10123456784"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('tracking_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Must be unique tracking number from delivery partner</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Partner *</label>
                <select name="delivery_partner" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Select delivery partner</option>
                    <option value="FEDEX" {{ old('delivery_partner') === 'FEDEX' ? 'selected' : '' }}>FedEx</option>
                    <option value="UPS" {{ old('delivery_partner') === 'UPS' ? 'selected' : '' }}>UPS</option>
                    <option value="AMAZON" {{ old('delivery_partner') === 'AMAZON' ? 'selected' : '' }}>Amazon Logistics</option>
                    <option value="USPS" {{ old('delivery_partner') === 'USPS' ? 'selected' : '' }}>USPS</option>
                    <option value="DHL" {{ old('delivery_partner') === 'DHL' ? 'selected' : '' }}>DHL</option>
                    <option value="Other" {{ old('delivery_partner') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('delivery_partner')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Image (Optional)</label>
                <input type="file" name="product_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">JPG, PNG (Max: 2MB)</p>
            </div>
        </div>
        
        <div class="mt-6 flex space-x-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                Create Shipment
            </button>
            <a href="{{ route('client.shipments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
