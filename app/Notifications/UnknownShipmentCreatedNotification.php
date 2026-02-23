<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UnknownShipmentCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Shipment $shipment,
        public string $creatorType, // 'admin' or 'agent'
        public string $creatorName
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'unknown_shipment_created',
            'message' => 'Unknown shipment created by ' . $this->creatorType . ': ' . $this->creatorName,
            'shipment_id' => $this->shipment->id,
            'shipment_code' => $this->shipment->shipment_code,
            'tracking_id' => $this->shipment->tracking_id,
            'creator_type' => $this->creatorType,
            'creator_name' => $this->creatorName,
            'client_id' => $this->shipment->client_id,
            'client_name' => $this->shipment->client?->name,
            'source' => $this->shipment->source,
            'delivery_partner' => $this->shipment->delivery_partner,
            'created_at' => $this->shipment->created_at->toDateTimeString(),
        ];
    }
}
