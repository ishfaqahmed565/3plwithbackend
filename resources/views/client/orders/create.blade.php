@extends('layouts.app')

@section('title', 'Create Order')

@php
    $color = 'green';
    $title = 'Create Order';
    $userName = auth('client')->user()->name;
    $logoutRoute = route('client.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('client.dashboard'), 'active' => false],
        ['label' => 'Shipments', 'url' => route('client.shipments.index'), 'active' => false],
        ['label' => 'Orders', 'url' => route('client.orders.index'), 'active' => true],
    ];
@endphp

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New Order</h2>
    
    <form method="POST" action="{{ route('client.orders.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Shipment *</label>
                <select name="shipment_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">-- Select Shipment --</option>
                    @foreach($shipments as $shipment)
                        <option value="{{ $shipment->id }}" {{ old('shipment_id') == $shipment->id ? 'selected' : '' }}>
                            {{ $shipment->shipment_code }} - {{ $shipment->product_name }} (Available: {{ $shipment->quantity_available }})
                        </option>
                    @endforeach
                </select>
                @if($shipments->isEmpty())
                    <p class="text-sm text-red-600 mt-1">No available shipments. Please create and receive a shipment first.</p>
                @endif
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tracking ID *</label>
                <input type="text" name="tracking_id" value="{{ old('tracking_id') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                <input type="number" name="quantity" value="{{ old('quantity') }}" required min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Phone *</label>
                <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Address *</label>
                <textarea name="customer_address" rows="3" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('customer_address') }}</textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Label * (Required)</label>
                <input type="file" name="label" accept=".pdf,.jpg,.jpeg,.png" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">PDF, JPG, PNG (Max: 5MB)</p>
            </div>
        </div>
        
        <div class="mt-6 flex space-x-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition" {{ $shipments->isEmpty() ? 'disabled' : '' }}>
                Create Order
            </button>
            <a href="{{ route('client.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
