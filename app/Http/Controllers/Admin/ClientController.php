<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount(['shipments', 'orders'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'group_id' => 'required|string|max:255|unique:clients,group_id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $plainPassword = $validated['password'];
        $validated['password'] = Hash::make($validated['password']);

        Client::create($validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client created successfully!')
            ->with('client_email', $validated['email'])
            ->with('client_password', $plainPassword);
    }

    public function show(Client $client)
    {
        $client->load(['shipments', 'orders']);
        return view('admin.clients.show', compact('client'));
    }
}
