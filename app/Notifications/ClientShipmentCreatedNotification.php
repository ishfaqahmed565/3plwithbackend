<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClientShipmentCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Shipment $shipment
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
            'type' => 'client_shipment_created',
            'message' => 'New shipment created by client: ' . $this->shipment->client->name,
            'shipment_id' => $this->shipment->id,
            'shipment_code' => $this->shipment->shipment_code,
            'tracking_id' => $this->shipment->tracking_id,
            'client_id' => $this->shipment->client_id,
            'client_name' => $this->shipment->client->name,
            'source' => $this->shipment->source,
            'product_count' => $this->shipment->products->count(),
            'created_at' => $this->shipment->created_at->toDateTimeString(),
        ];
    }
}
