<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\Settlement;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => Client::count(),
            'total_shipments' => Shipment::count(),
            'total_orders' => Order::count(),
            'pending_settlements' => Settlement::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
