<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // Get pending shipments for Scan-1
        $pendingShipments = Shipment::where('status', 'pending')
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get orders pending Scan-2
        $pendingScan2 = Order::where('status', 'pending_scan2')
            ->with(['client', 'shipment'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get orders pending Scan-3
        $pendingScan3 = Order::where('status', 'prepared_for_delivery')
            ->with(['client', 'shipment'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('agent.dashboard', compact('pendingShipments', 'pendingScan2', 'pendingScan3'));
    }
}
