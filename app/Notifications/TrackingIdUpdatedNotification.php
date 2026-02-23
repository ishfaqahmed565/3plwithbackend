<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TrackingIdUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Shipment $shipment,
        public ?string $oldTrackingId,
        public string $newTrackingId
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
            'type' => 'tracking_id_updated',
            'message' => 'Tracking ID updated by client: ' . $this->shipment->client->name,
            'shipment_id' => $this->shipment->id,
            'shipment_code' => $this->shipment->shipment_code,
            'old_tracking_id' => $this->oldTrackingId,
            'new_tracking_id' => $this->newTrackingId,
            'client_id' => $this->shipment->client_id,
            'client_name' => $this->shipment->client->name,
            'updated_at' => now()->toDateTimeString(),
        ];
    }
}
