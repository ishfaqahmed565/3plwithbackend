<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\ShipmentAttachment;
use App\Models\ShipmentLineItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ShipmentService
{
    public function createShipment(array $data, ?UploadedFile $image = null, array $attachments = []): Shipment
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

        $shipment = Shipment::create($data);

        if (!empty($attachments)) {
            $this->storeAttachments($shipment, $attachments, 'client', 'client_upload');
        }

        return $shipment;
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
        array $proofFiles = [],
        array $barcodes = []
    ): Shipment {
        // Update shipment with verification details
        $shipment->update([
            'status' => 'received_in_warehouse',
            'scan1_at' => now(),
            'rack_location' => $rackLocation,
            'received_quantity' => $receivedQuantity,
            'product_condition' => $productCondition,
            'scan1_notes' => $notes,
            'quantity_available' => $receivedQuantity, // Use received quantity as available
        ]);

        if (!empty($proofFiles)) {
            $this->storeAttachments($shipment, $proofFiles, 'agent', 'scan1_proof');
        }

        if (!empty($barcodes)) {
            foreach ($barcodes as $barcode) {
                $barcodeValue = trim($barcode);
                if ($barcodeValue === '') {
                    continue;
                }

                ShipmentLineItem::create([
                    'shipment_id' => $shipment->id,
                    'barcode' => $barcodeValue,
                    'lookup_url' => 'https://www.barcodelookup.com/' . $barcodeValue,
                ]);
            }
        }
        
        return $shipment->fresh();
    }

    public function storeAttachments(Shipment $shipment, array $files, string $uploadedBy, string $context): void
    {
        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store('shipment-attachments', 'public');

            ShipmentAttachment::create([
                'shipment_id' => $shipment->id,
                'uploaded_by' => $uploadedBy,
                'context' => $context,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
            ]);
        }
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
