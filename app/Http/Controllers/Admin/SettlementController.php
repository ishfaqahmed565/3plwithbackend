<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Services\SettlementService;

class SettlementController extends Controller
{
    public function __construct(private SettlementService $settlementService)
    {
    }

    public function index()
    {
        $settlements = Settlement::with(['order', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.settlements.index', compact('settlements'));
    }

    public function approve(Settlement $settlement)
    {
        $this->settlementService->approveSettlement($settlement);
        return back()->with('success', 'Settlement approved successfully!');
    }

    public function markAsPaid(Settlement $settlement)
    {
        $this->settlementService->markSettlementAsPaid($settlement);
        return back()->with('success', 'Settlement marked as paid!');
    }
}
