<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RackLocation;
use Illuminate\Http\Request;

class RackLocationController extends Controller
{
    public function index()
    {
        $rackLocations = RackLocation::withCount('shipments')
            ->orderBy('code')
            ->paginate(50);
        return view('admin.rack-locations.index', compact('rackLocations'));
    }

    public function create()
    {
        return view('admin.rack-locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:rack_locations,code',
            'zone' => 'required|string|max:255',
            'aisle' => 'required|string|max:255',
            'rack' => 'required|string|max:255',
        ]);

        $validated['status'] = 'available';

        RackLocation::create($validated);

        return redirect()->route('admin.rack-locations.index')
            ->with('success', 'Rack location created successfully!');
    }
}
