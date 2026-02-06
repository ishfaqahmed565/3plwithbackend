@extends('layouts.app')

@section('title', 'Scan-2: Order Preparation')

@php
    $color = 'purple';
    $title = 'Agent Dashboard';
    $userName = auth('agent')->user()->name;
    $logoutRoute = route('agent.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('agent.dashboard'), 'active' => false],
        ['label' => 'Scan-1 (Shipment)', 'url' => route('agent.scan.shipment'), 'active' => false],
        ['label' => 'Scan-2 (Prep)', 'url' => route('agent.scan.order-prep'), 'active' => true],
        ['label' => 'Scan-3 (Handover)', 'url' => route('agent.scan.order-handover'), 'active' => false],
    ];
@endphp

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-purple-600 mb-2">Scan-2: Order Preparation</h2>
            <p class="text-gray-600">Scan order code to mark as prepared for delivery</p>
        </div>
        
        <form method="POST" action="{{ route('agent.scan.order-prep') }}">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Code</label>
                <input type="text" name="order_code" autofocus required
                       placeholder="ORD-XXXXXXXX"
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-lg font-semibold py-4 rounded-lg transition">
                Process Scan-2
            </button>
        </form>
        
        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
            <h3 class="font-semibold text-blue-900 mb-2">Scan-2 Effect:</h3>
            <ul class="list-disc list-inside text-sm text-blue-800 space-y-1">
                <li>Status: pending_scan2 → prepared_for_delivery</li>
                <li>Order ready for handover</li>
                <li>Scan-2 timestamp recorded</li>
            </ul>
        </div>
    </div>
</div>
@endsection
