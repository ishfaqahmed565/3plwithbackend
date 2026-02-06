@extends('layouts.app')

@section('title', 'Create Admin')

@php
    $color = 'blue';
    $title = 'Create New Admin';
    $userName = auth('admin')->user()->name;
    $logoutRoute = route('admin.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'active' => false],
        ['label' => 'Clients', 'url' => route('admin.clients.index'), 'active' => false],
        ['label' => 'Agents', 'url' => route('admin.agents.index'), 'active' => false],
        ['label' => 'Admins', 'url' => route('admin.admins.index'), 'active' => true],
        ['label' => 'Settlements', 'url' => route('admin.settlements.index'), 'active' => false],
    ];
@endphp

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Admin Name *</label>
                <input type="text" name="name" id="name" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('name') }}" autofocus>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                <input type="email" name="email" id="email" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                <input type="password" name="password" id="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.admins.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Create Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
