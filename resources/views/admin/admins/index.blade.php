@extends('layouts.app')

@section('title', 'Manage Admins')

@php
    $color = 'blue';
    $title = 'Manage Admins';
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
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-900">Administrators</h2>
        <a href="{{ route('admin.admins.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Create New Admin
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($admins as $admin)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $admin->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $admin->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $admin->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $admin->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        No admins found. <a href="{{ route('admin.admins.create') }}" class="text-blue-600 hover:text-blue-800">Create one now</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($admins->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $admins->links() }}
    </div>
    @endif
</div>
@endsection
