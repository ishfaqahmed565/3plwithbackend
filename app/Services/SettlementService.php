<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Settlement;

class SettlementService
{
    /**
     * Create settlement for an order after Scan-3
     * This is the ONLY way settlements should be created
     */
    public function createSettlementForOrder(Order $order): Settlement
    {
        // Check if settlement already exists
        if ($order->settlement()->exists()) {
            return $order->settlement;
        }

        // Calculate amount (can be customized based on business rules)
        $amount = $this->calculateSettlementAmount($order);

        return Settlement::create([
            'order_id' => $order->id,
            'client_id' => $order->client_id,
            'amount' => $amount,
            'status' => 'pending',
        ]);
    }

    /**
     * Calculate settlement amount
     * Currently using a simple flat rate, can be expanded with rules engine
     */
    private function calculateSettlementAmount(Order $order): float
    {
        // Example: $5 per unit
        $ratePerUnit = 5.00;
        return $order->quantity * $ratePerUnit;
    }

    public function getClientSettlements(int $clientId)
    {
        return Settlement::where('client_id', $clientId)
            ->with(['order'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function approveSettlement(Settlement $settlement): Settlement
    {
        $settlement->approve();
        return $settlement->fresh();
    }

    public function markSettlementAsPaid(Settlement $settlement): Settlement
    {
        $settlement->markAsPaid();
        return $settlement->fresh();
    }

    public function getPendingSettlements()
    {
        return Settlement::where('status', 'pending')
            ->with(['order', 'client'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
