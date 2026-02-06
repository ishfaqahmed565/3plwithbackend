<?php

namespace App\Services;

use App\Models\Shipment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ShipmentService
{
    public function createShipment(array $data, ?UploadedFile $image = null): Shipment
    {
        // Generate unique shipment code
        $data['shipment_code'] = 'SHP-' . strtoupper(uniqid());
        $data['quantity_available'] = $data['quantity_total'];
        $data['status'] = 'pending';

        // Handle image upload
        if ($image) {
            $path = $image->store('shipments', 'public');
            $data['product_image_path'] = $path;
        }

        return Shipment::create($data);
    }

    public function receiveShipment(Shipment $shipment): Shipment
    {
        $shipment->markAsReceived();
        return $shipment->fresh();
    }
    
    public function receiveShipmentWithVerification(
        Shipment $shipment,
        string $rackLocation,
        int $receivedQuantity,
        string $productCondition,
        ?string $notes,
        $proofImage
    ): Shipment {
        // Upload proof image
        $imagePath = $proofImage->store('scan1-proofs', 'public');
        
        // Update shipment with verification details
        $shipment->update([
            'status' => 'received_in_warehouse',
            'scan1_at' => now(),
            'rack_location' => $rackLocation,
            'received_quantity' => $receivedQuantity,
            'product_condition' => $productCondition,
            'scan1_notes' => $notes,
            'scan1_image_path' => $imagePath,
            'quantity_available' => $receivedQuantity, // Use received quantity as available
        ]);
        
        return $shipment->fresh();
    }

    public function getClientShipments(int $clientId)
    {
        return Shipment::where('client_id', $clientId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAvailableInventory(int $clientId)
    {
        return Shipment::where('client_id', $clientId)
            ->where('status', 'received_in_warehouse')
            ->where('quantity_available', '>', 0)
            ->get();
    }
}
