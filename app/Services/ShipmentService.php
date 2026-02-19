<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\ShipmentAttachment;
use App\Models\ShipmentLineItem;
use App\Models\ShipmentProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ShipmentService
{
    public function createShipment(array $data, ?UploadedFile $image = null, array $attachments = [], array $products = []): Shipment
    {
        // Generate unique shipment code
        $data['shipment_code'] = 'SHP-' . strtoupper(uniqid());
        
        // Calculate quantity from products
        $quantityTotal = array_sum(array_column($products, 'quantity')) ?: 1;
        
        // Generate product name from products array
        $productName = !empty($products) 
            ? implode(', ', array_slice(array_column($products, 'name'), 0, 3)) . (count($products) > 3 ? '...' : '')
            : 'Multiple Products';
        
        $shipment = Shipment::create([
            'shipment_code' => $data['shipment_code'] ?? ('SHP-' . strtoupper(uniqid())),
            'client_id' => $data['client_id'] ?? auth()->id(),
            'source' => $data['source'],
            'product_name' => $productName,
            'product_description' => $data['product_description'] ?? null,
            'category' => $data['category'] ?? null,
            'quantity_total' => $quantityTotal,
            'quantity_available' => $quantityTotal,
            'tracking_id' => $data['tracking_id'],
            'delivery_partner' => $data['delivery_partner'],
            'status' => 'pending',
        ]);
        
            // Handle image upload
            if ($image) {
                $path = $image->store('shipments', 'public');
                $data['product_image_path'] = $path;
            }

        // $data already used to create shipment above

        if (!empty($attachments)) {
            $this->storeAttachments($shipment, $attachments, 'client', 'client_upload');
        }

        if (!empty($products)) {
            foreach ($products as $p) {
                ShipmentProduct::create([
                    'shipment_id' => $shipment->id,
                    'name' => $p['name'] ?? 'Unnamed',
                    'description' => $p['description'] ?? null,
                    'quantity_expected' => (int)($p['quantity'] ?? 0),
                    'quantity_available' => (int)($p['quantity'] ?? 0),
                ]);
            }
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
        ?string $rackLocation,
        int $receivedQuantity,
        string $productCondition,
        ?string $notes,
        array $proofFiles = [],
        array $barcodes = [],
        ?int $agentId = null,
        ?int $warehouse = null
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
            'received_by_agent_id' => $agentId,
            'received_in_warehouse' => $warehouse,
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
