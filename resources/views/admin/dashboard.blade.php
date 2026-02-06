@extends('layouts.app')

@section('title', 'Admin Dashboard')

@php
    $color = 'blue';
    $title = 'Admin Dashboard';
    $userName = auth('admin')->user()->name;
    $logoutRoute = route('admin.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'active' => true],
        ['label' => 'Clients', 'url' => route('admin.clients.index'), 'active' => false],
        ['label' => 'Agents', 'url' => route('admin.agents.index'), 'active' => false],
        ['label' => 'Admins', 'url' => route('admin.admins.index'), 'active' => false],
       
        ['label' => 'Settlements', 'url' => route('admin.settlements.index'), 'active' => false],
    ];
@endphp

@section('content')
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

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('admin.clients.create') }}" class="block p-4 border-2 border-blue-200 rounded-lg hover:border-blue-400 transition">
            <h3 class="font-semibold text-blue-600">Create Client</h3>
            <p class="text-sm text-gray-600 mt-1">Add new client to the system</p>
        </a>
        <a href="{{ route('admin.settlements.index') }}" class="block p-4 border-2 border-blue-200 rounded-lg hover:border-blue-400 transition">
            <h3 class="font-semibold text-blue-600">Manage Settlements</h3>
            <p class="text-sm text-gray-600 mt-1">Review and approve settlements</p>
        </a>
    </div>
</div>
@endsection
