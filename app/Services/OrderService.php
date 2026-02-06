<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderService
{
    public function createOrder(array $data, UploadedFile $label): Order
    {
        return DB::transaction(function () use ($data, $label) {
            $shipment = Shipment::findOrFail($data['shipment_id']);

            // Validate inventory
            if (!$shipment->canCreateOrder($data['quantity'])) {
                throw new \Exception('Insufficient inventory or shipment not received yet.');
            }

            // Generate unique order code
            $data['order_code'] = 'ORD-' . strtoupper(uniqid());
            $data['status'] = 'pending_scan2';

            // Handle label upload
            $path = $label->store('labels', 'public');
            $data['label_file_path'] = $path;

            // Decrease inventory
            $shipment->decreaseInventory($data['quantity']);

            return Order::create($data);
        });
    }

    public function getClientOrders(int $clientId)
    {
        return Order::where('client_id', $clientId)
            ->with(['shipment'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function performScan2(Order $order): Order
    {
        if (!$order->canPerformScan2()) {
            throw new \Exception('Order is not ready for Scan-2');
        }

        $order->performScan2();
        return $order->fresh();
    }

    public function performScan3(Order $order): Order
    {
        if (!$order->canPerformScan3()) {
            throw new \Exception('Order is not ready for Scan-3');
        }

        $order->performScan3();
        
        // Trigger settlement creation
        app(SettlementService::class)->createSettlementForOrder($order);

        return $order->fresh();
    }
}
