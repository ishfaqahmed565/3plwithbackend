@extends('layouts.app')

@section('title', 'Admin Dashboard')

@php
    $color = 'blue';
    $title = 'Admin Dashboard';
    $userName = auth('admin')->user()->name;
    $logoutRoute = route('admin.logout');
    // $navigation is now provided by AdminNavigationComposer
@endphp

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Pending Shipments</h3>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Awaiting Scan-1</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Received Shipments</h3>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['received_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">In Warehouse</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">No Tracking ID</h3>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['no_tracking_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Pending shipments</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">No Rack Assignment</h3>
        <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['no_rack_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Received shipments</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Unassigned Shipments</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['unassigned_shipments'] }}</p>
        <p class="text-xs text-gray-500 mt-1">No client assigned</p>
    </div>
</div>
<!-- <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Clients</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['total_clients'] }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Shipments</h3>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['total_shipments'] }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['total_orders'] }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Pending Settlements</h3>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['pending_settlements'] }}</p>
    </div>
</div> -->

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.clients.create') }}" class="block p-4 border-2 border-blue-200 rounded-lg hover:border-blue-400 transition">
            <h3 class="font-semibold text-blue-600">Create Client</h3>
            <p class="text-sm text-gray-600 mt-1">Add new client to the system</p>
        </a>
        <a href="{{ route('admin.settlements.index') }}" class="block p-4 border-2 border-blue-200 rounded-lg hover:border-blue-400 transition">
            <h3 class="font-semibold text-blue-600">Manage Settlements</h3>
            <p class="text-sm text-gray-600 mt-1">Review and approve settlements</p>
        </a>
        <a href="{{ route('admin.shipments.create-unknown') }}" class="block p-4 border-2 border-orange-200 rounded-lg hover:border-orange-400 transition">
            <h3 class="font-semibold text-orange-600">Create Unknown Shipment</h3>
            <p class="text-sm text-gray-600 mt-1">Register unidentified packages</p>
        </a>
    </div>
</div>

@endsection
