@extends('layouts.app')

@section('title', 'Client Dashboard')

@php
    $color = 'green';
    $title = 'Client Dashboard';
    $userName = auth('client')->user()->name;
    $logoutRoute = route('client.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('client.dashboard'), 'active' => true],
        ['label' => 'Shipments', 'url' => route('client.shipments.index'), 'active' => false],
    ];
@endphp

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Group ID</h3>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ auth('client')->user()->group_id }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Shipments</h3>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ auth('client')->user()->shipments()->count() }}</p>
    </div>
    
    <!-- <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ auth('client')->user()->orders()->count() }}</p>
    </div> -->
</div>

<div class="bg-white rounded-lg shadow p-6">
  Quick Actions
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('client.shipments.create') }}" class="block p-4 border-2 border-green-200 rounded-lg hover:border-green-400 transition">
            <h3 class="font-semibold text-green-600">Create Shipment</h3>
            <p class="text-sm text-gray-600 mt-1">Add new inventory to warehouse</p>
        </a>
        <!-- <a href="{{ route('client.orders.create') }}" class="block p-4 border-2 border-green-200 rounded-lg hover:border-green-400 transition">
            <h3 class="font-semibold text-green-600">Create Order</h3>
            <p class="text-sm text-gray-600 mt-1">Create customer delivery order</p>
        </a> -->
    </div>
</div>
@endsection
