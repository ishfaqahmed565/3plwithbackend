@extends('layouts.app')

@section('title', 'My Orders')

@php
    $color = 'green';
    $title = 'My Orders';
    $userName = auth('client')->user()->name;
    $logoutRoute = route('client.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('client.dashboard'), 'active' => false],
        ['label' => 'Shipments', 'url' => route('client.shipments.index'), 'active' => false],
        ['label' => 'Orders', 'url' => route('client.orders.index'), 'active' => true],
    ];
@endphp

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900">My Orders</h2>
    <a href="{{ route('client.orders.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
        + Create Order
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $order->order_code }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->status === 'pending_scan2')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Scan-2</span>
                        @elseif($order->status === 'prepared_for_delivery')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Prepared</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Delivered</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        No orders found. <a href="{{ route('client.orders.create') }}" class="text-green-600 hover:underline">Create your first order</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
