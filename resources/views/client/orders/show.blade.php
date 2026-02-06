@extends('layouts.app')

@section('title', 'Order Details')

@php
    $color = 'green';
    $title = 'Order Details';
    $userName = auth('client')->user()->name;
    $logoutRoute = route('client.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('client.dashboard'), 'active' => false],
        ['label' => 'Shipments', 'url' => route('client.shipments.index'), 'active' => false],
        ['label' => 'Orders', 'url' => route('client.orders.index'), 'active' => true],
    ];
@endphp

@section('content')
<div class="mb-6">
    <a href="{{ route('client.orders.index') }}" class="text-green-600 hover:text-green-800">← Back to Orders</a>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Order {{ $order->order_code }}</h2>
            <p class="text-sm text-gray-600 mt-1">Created {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <span class="px-3 py-1 rounded text-sm font-semibold
            @if($order->status === 'pending_scan2') bg-yellow-100 text-yellow-800
            @elseif($order->status === 'prepared_for_delivery') bg-blue-100 text-blue-800
            @elseif($order->status === 'handover_to_delivery_partner') bg-green-100 text-green-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ str_replace('_', ' ', ucfirst($order->status)) }}
        </span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-sm font-medium text-gray-500 mb-3">Customer Information</h3>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm text-gray-600">Name</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Email</dt>
                    <dd class="text-sm text-gray-900">{{ $order->customer_email }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Phone</dt>
                    <dd class="text-sm text-gray-900">{{ $order->customer_phone }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Address</dt>
                    <dd class="text-sm text-gray-900">{{ $order->customer_address }}</dd>
                </div>
            </dl>
        </div>
        
        <div>
            <h3 class="text-sm font-medium text-gray-500 mb-3">Order Information</h3>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm text-gray-600">Shipment</dt>
                    <dd class="text-sm font-medium text-gray-900">
                        <a href="{{ route('client.shipments.show', $order->shipment) }}" class="text-green-600 hover:text-green-800">
                            {{ $order->shipment->shipment_code }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Product</dt>
                    <dd class="text-sm text-gray-900">{{ $order->shipment->product_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Quantity</dt>
                    <dd class="text-2xl font-bold text-gray-900">{{ $order->quantity }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    @if($order->label_path)
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Shipping Label</h3>
        @php
            $extension = pathinfo($order->label_path, PATHINFO_EXTENSION);
        @endphp
        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
            <img src="{{ Storage::url($order->label_path) }}" alt="Shipping Label" class="max-w-md rounded-lg shadow">
        @else
            <a href="{{ Storage::url($order->label_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
                Download Label (PDF)
            </a>
        @endif
    </div>
    @endif
    
    <div class="border-t pt-6">
        <h3 class="text-sm font-medium text-gray-500 mb-3">Scan Timeline</h3>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if($order->scan2_at)
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @else
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                        </div>
                    @endif
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Scan-2: Order Preparation</p>
                    <p class="text-sm text-gray-600">
                        @if($order->scan2_at)
                            Completed on {{ $order->scan2_at->format('M d, Y h:i A') }}
                        @else
                            Pending - Awaiting agent to prepare order
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if($order->scan3_at)
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @else
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                        </div>
                    @endif
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Scan-3: Handover to Delivery Partner</p>
                    <p class="text-sm text-gray-600">
                        @if($order->scan3_at)
                            Completed on {{ $order->scan3_at->format('M d, Y h:i A') }}
                        @else
                            Pending - Will trigger settlement creation
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settlement Information -->
@if($order->settlement)
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-bold text-gray-900 mb-4">Settlement Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <h3 class="text-sm font-medium text-gray-500">Settlement ID</h3>
            <p class="text-lg font-mono font-semibold text-gray-900 mt-1">#{{ $order->settlement->id }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Amount</h3>
            <p class="text-lg font-bold text-gray-900 mt-1">${{ number_format($order->settlement->amount, 2) }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Status</h3>
            <p class="mt-1">
                <span class="px-2 py-1 rounded text-sm font-semibold
                    @if($order->settlement->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->settlement->status === 'approved') bg-blue-100 text-blue-800
                    @elseif($order->settlement->status === 'paid') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($order->settlement->status) }}
                </span>
            </p>
        </div>
    </div>
</div>
@endif
@endsection
