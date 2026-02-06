@extends('layouts.app')

@section('title', 'Settlements')

@php
    $color = 'blue';
    $title = 'Settlements Management';
    $userName = auth('admin')->user()->name;
    $logoutRoute = route('admin.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'active' => false],
        ['label' => 'Clients', 'url' => route('admin.clients.index'), 'active' => false],
        ['label' => 'Agents', 'url' => route('admin.agents.index'), 'active' => false],
        ['label' => 'Admins', 'url' => route('admin.admins.index'), 'active' => false],
        ['label' => 'Settlements', 'url' => route('admin.settlements.index'), 'active' => true],
    ];
@endphp

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Settlements</h2>
        <p class="text-sm text-gray-600 mt-1">Review and approve settlements created after Scan-3 operations</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Settlement ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($settlements as $settlement)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-mono text-sm text-gray-900">#{{ $settlement->id }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $settlement->order->order_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $settlement->client->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        ${{ number_format($settlement->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($settlement->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($settlement->status === 'approved') bg-blue-100 text-blue-800
                            @elseif($settlement->status === 'paid') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($settlement->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $settlement->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($settlement->status === 'pending')
                            <form method="POST" action="{{ route('admin.settlements.approve', $settlement) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-600 hover:text-blue-800 mr-2">Approve</button>
                            </form>
                        @elseif($settlement->status === 'approved')
                            <form method="POST" action="{{ route('admin.settlements.mark-paid', $settlement) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">Mark Paid</button>
                            </form>
                        @else
                            <span class="text-gray-400">No actions</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No settlements found. Settlements are automatically created when Scan-3 is performed.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($settlements->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $settlements->links() }}
    </div>
    @endif
</div>

<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <h3 class="text-sm font-semibold text-blue-900 mb-2">Settlement Workflow:</h3>
    <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
        <li><strong>Pending:</strong> Settlement created automatically after Scan-3</li>
        <li><strong>Approved:</strong> Admin reviews and approves the settlement amount</li>
        <li><strong>Paid:</strong> Payment processed and marked as complete</li>
    </ol>
</div>
@endsection
