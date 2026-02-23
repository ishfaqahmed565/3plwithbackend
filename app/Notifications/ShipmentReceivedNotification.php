<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentReceivedNotification extends Notification implements ShouldQueue
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
        // Only send mail if mail is properly configured
        if (config('mail.mailers.smtp.host')) {
            return ['mail'];
        }
        
        // If mail is not configured, don't send anything (graceful degradation)
        return [];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Shipment Received - ' . $this->shipment->shipment_code)
            ->view(
                ['mail.shipment.received', 'mail.shipment.received-text'],
                [
                    'shipment' => $this->shipment,
                    'client' => $notifiable,
                    'businessName' => config('app.name', 'SmartComm TIC3PL'),
                ]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'shipment_id' => $this->shipment->id,
            'shipment_code' => $this->shipment->shipment_code,
            'tracking_id' => $this->shipment->tracking_id,
            'received_at' => $this->shipment->received_at,
        ];
    }
}
