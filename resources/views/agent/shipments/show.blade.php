@extends('layouts.app')

@section('title', 'Shipment Details')

@php
    $color = 'purple';
    $title = 'Shipment Details';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('agent.dashboard'), 'active' => false],
        ['label' => 'Scan-1 (Shipment)', 'url' => route('agent.scan.shipment'), 'active' => false],
        ['label' => 'Scan-2 (Prep)', 'url' => route('agent.scan.order-prep'), 'active' => false],
        ['label' => 'Scan-3 (Handover)', 'url' => route('agent.scan.order-handover'), 'active' => false],
    ];
@endphp

@section('content')
<div class="mb-6">
    <button onclick="window.close()" class="text-purple-600 hover:text-purple-800">← Close Window</button>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $shipment->product_name }}</h2>
            <p class="text-sm text-gray-600 mt-1">Shipment Code: <span class="font-mono font-semibold">{{ $shipment->shipment_code }}</span></p>
        </div>
        <span class="px-3 py-1 rounded text-sm font-semibold
            @if($shipment->status === 'pending') bg-yellow-100 text-yellow-800
            @elseif($shipment->status === 'received_in_warehouse') bg-green-100 text-green-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ str_replace('_', ' ', ucfirst($shipment->status)) }}
        </span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-sm font-medium text-gray-500 mb-3">Shipment Information</h3>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm text-gray-600">Tracking ID</dt>
                    <dd class="text-sm font-mono font-semibold text-gray-900">{{ $shipment->tracking_id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Delivery Partner</dt>
                    <dd class="text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ $shipment->delivery_partner }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Source</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $shipment->source }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Category</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ ucfirst($shipment->category) }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Client</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $shipment->client->name }} (Group ID: {{ $shipment->client->group_id }})</dd>
                </div>
            </dl>
        </div>
        
        <div>
            <h3 class="text-sm font-medium text-gray-500 mb-3">Quantity</h3>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm text-gray-600">Total Quantity</dt>
                    <dd class="text-2xl font-bold text-gray-900">{{ $shipment->quantity_total }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Available Quantity</dt>
                    <dd class="text-2xl font-bold text-purple-600">{{ $shipment->quantity_available }}</dd>
                </div>
                @if($shipment->quantity_available < $shipment->quantity_total)
                <div>
                    <dt class="text-sm text-gray-600">Used in Orders</dt>
                    <dd class="text-sm font-medium text-blue-600">{{ $shipment->quantity_total - $shipment->quantity_available }} units</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
    
    @if($shipment->description)
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
        <p class="text-sm text-gray-700">{{ $shipment->description }}</p>
    </div>
    @endif
    
    @if($shipment->attachments->where('context', 'client_upload')->count())
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Client Attachments</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($shipment->attachments->where('context', 'client_upload') as $attachment)
                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="border rounded-lg p-3 bg-gray-50 hover:border-purple-400 transition">
                    <p class="text-sm font-medium text-gray-700 mb-2 break-all">{{ $attachment->original_name }}</p>
                    @if(str_starts_with($attachment->mime_type, 'image/'))
                        <img src="{{ Storage::url($attachment->file_path) }}" alt="Attachment" class="w-full h-40 object-cover rounded">
                    @else
                        <div class="flex items-center justify-center h-40 bg-white border border-dashed rounded text-sm text-gray-600">PDF Document</div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Scan-1 Verification Details -->
    @if($shipment->scan1_at)
    <div class="mb-6 border-2 border-green-200 rounded-lg p-6 bg-green-50">
        <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Scan-1 Verification Completed
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dl class="space-y-3">
                    @if($shipment->rackLocation)
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Rack Location</dt>
                        <dd class="text-sm mt-1">
                            <span class="px-3 py-1 bg-white border-2 border-green-300 rounded font-mono font-bold text-green-800">
                                {{ $shipment->rackLocation->code }}
                            </span>
                            <span class="ml-2 text-xs text-gray-600">
                                Zone {{ $shipment->rackLocation->zone }} · Aisle {{ $shipment->rackLocation->aisle }} · Rack {{ $shipment->rackLocation->rack }}
                            </span>
                        </dd>
                    </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Received Quantity</dt>
                        <dd class="text-lg font-bold text-gray-900">
                            {{ $shipment->received_quantity ?? $shipment->quantity_total }} units
                            @if($shipment->received_quantity && $shipment->received_quantity != $shipment->quantity_total)
                                <span class="text-sm font-normal text-orange-600">(Expected: {{ $shipment->quantity_total }})</span>
                            @endif
                        </dd>
                    </div>
                    
                    @if($shipment->product_condition)
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Product Condition</dt>
                        <dd class="text-sm mt-1">
                            <span class="px-2 py-1 rounded text-xs font-semibold uppercase
                                @if($shipment->product_condition === 'excellent') bg-green-100 text-green-800
                                @elseif($shipment->product_condition === 'good') bg-blue-100 text-blue-800
                                @elseif($shipment->product_condition === 'fair') bg-yellow-100 text-yellow-800
                                @elseif($shipment->product_condition === 'damaged') bg-red-100 text-red-800
                                @endif">
                                {{ $shipment->product_condition }}
                            </span>
                        </dd>
                    </div>
                    @endif
                    
                    @if($shipment->scan1_notes)
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Agent Notes</dt>
                        <dd class="text-sm text-gray-900 mt-1 bg-white p-3 rounded border border-green-200">
                            {{ $shipment->scan1_notes }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
            
            <div>
                @if($shipment->attachments->where('context', 'scan1_proof')->count())
                <div>
                    <dt class="text-sm font-medium text-gray-700 mb-2">Proof of Receipt</dt>
                    <dd>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($shipment->attachments->where('context', 'scan1_proof') as $attachment)
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="block border rounded-lg p-3 bg-white hover:border-green-400 transition">
                                    <p class="text-xs text-gray-600 mb-2 break-all">{{ $attachment->original_name }}</p>
                                    @if(str_starts_with($attachment->mime_type, 'image/'))
                                        <img src="{{ Storage::url($attachment->file_path) }}"
                                             alt="Scan-1 Proof"
                                             class="w-full h-40 object-cover rounded">
                                    @else
                                        <div class="flex items-center justify-center h-40 bg-gray-50 border border-dashed rounded text-sm text-gray-600">PDF Document</div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </dd>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if($shipment->lineItems->count())
    <div class="border-t pt-6">
        <h3 class="text-sm font-medium text-gray-500 mb-3">Line Items (Scanned Barcodes)</h3>
        <ul class="space-y-2">
            @foreach($shipment->lineItems as $item)
                <li class="flex items-center justify-between bg-gray-50 border rounded-lg px-4 py-3">
                    <span class="font-mono text-sm text-gray-900">{{ $item->barcode }}</span>
                    <a href="{{ $item->lookup_url }}" target="_blank" class="text-purple-600 hover:text-purple-800 text-sm font-semibold">Open Barcode Lookup</a>
                </li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="border-t pt-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Timeline</h3>
        <dl class="space-y-2">
            <div>
                <dt class="text-sm text-gray-600">Created</dt>
                <dd class="text-sm text-gray-900">{{ $shipment->created_at->format('M d, Y h:i A') }}</dd>
            </div>
            @if($shipment->scan1_at)
            <div>
                <dt class="text-sm text-gray-600">Received in Warehouse (Scan-1)</dt>
                <dd class="text-sm text-gray-900">{{ $shipment->scan1_at->format('M d, Y h:i A') }}</dd>
            </div>
            @endif
        </dl>
    </div>
</div>

<!-- Related Orders -->
@if($shipment->orders->count() > 0)
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Related Orders</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($shipment->orders as $order)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono">{{ $order->order_code }}</td>
                    <td class="px-6 py-4 text-sm">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $order->quantity }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($order->status === 'pending_scan2') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'prepared_for_delivery') bg-blue-100 text-blue-800
                            @elseif($order->status === 'handover_to_delivery_partner') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ str_replace('_', ' ', ucfirst($order->status)) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
