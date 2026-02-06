@extends('layouts.app')

@section('title', 'Manage Clients')

@php
    $color = 'blue';
    $title = 'Manage Clients';
    $userName = auth('admin')->user()->name;
    $logoutRoute = route('admin.logout');
    $navigation = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'active' => false],
        ['label' => 'Clients', 'url' => route('admin.clients.index'), 'active' => true],
        ['label' => 'Agents', 'url' => route('admin.agents.index'), 'active' => false],
        ['label' => 'Admins', 'url' => route('admin.admins.index'), 'active' => false],
        ['label' => 'Settlements', 'url' => route('admin.settlements.index'), 'active' => false],
    ];
@endphp

@section('content')
<!-- Success Modal with Credentials -->
@if(session('success') && session('client_email') && session('client_password'))
<div id="credentialsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-2">Client Created Successfully!</h3>
            <p class="text-sm text-center text-gray-600 mb-6">Save these credentials to share with the client</p>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="flex gap-2">
                        <input type="text" id="clientEmail" value="{{ session('client_email') }}" readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm">
                        <button onclick="copyToClipboard('clientEmail', this)" 
                                class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition">
                            Copy
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="flex gap-2">
                        <input type="text" id="clientPassword" value="{{ session('client_password') }}" readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm font-mono">
                        <button onclick="copyToClipboard('clientPassword', this)" 
                                class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition">
                            Copy
                        </button>
                    </div>
                </div>
                
                <div class="pt-2">
                    <button onclick="copyAllCredentials()" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        Copy All Credentials
                    </button>
                </div>
            </div>
            
            <button onclick="closeModal()" 
                    class="mt-6 w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId, button) {
    const input = document.getElementById(elementId);
    input.select();
    document.execCommand('copy');
    
    const originalText = button.textContent;
    button.textContent = 'Copied!';
    button.classList.remove('bg-blue-100', 'hover:bg-blue-200');
    button.classList.add('bg-green-100');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-green-100');
        button.classList.add('bg-blue-100', 'hover:bg-blue-200');
    }, 2000);
}

function copyAllCredentials() {
    const email = document.getElementById('clientEmail').value;
    const password = document.getElementById('clientPassword').value;
    const credentials = `Email: ${email}\nPassword: ${password}`;
    
    navigator.clipboard.writeText(credentials).then(() => {
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied All!';
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    });
}

function closeModal() {
    document.getElementById('credentialsModal').style.display = 'none';
}
</script>
@endif

<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-900">Clients</h2>
        <a href="{{ route('admin.clients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Create New Client
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipments</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clients as $client)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-mono text-sm text-gray-900">{{ $client->group_id }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $client->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $client->shipments_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $client->orders_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.clients.show', $client) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No clients found. <a href="{{ route('admin.clients.create') }}" class="text-blue-600 hover:text-blue-800">Create one now</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($clients->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $clients->links() }}
    </div>
    @endif
</div>
@endsection
