<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Shipment;
use App\Notifications\ShipmentReceivedNotification;
use Illuminate\Console\Command;

class TestShipmentEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:shipment-email {shipment_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test shipment received email notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shipmentId = $this->argument('shipment_id');
        
        $shipment = Shipment::with(['client', 'products', 'attachments'])->find($shipmentId);
        
        if (!$shipment) {
            $this->error("Shipment #{$shipmentId} not found!");
            return 1;
        }
        
        if (!$shipment->client) {
            $this->error("Shipment #{$shipmentId} has no client assigned!");
            return 1;
        }
        
        $this->info("Sending test email for Shipment: {$shipment->shipment_code}");
        $this->info("To: {$shipment->client->email}");
        $this->info("Client: {$shipment->client->name}");
        
        try {
            $shipment->client->notify(new ShipmentReceivedNotification($shipment));
            $this->info("\n✓ Email sent successfully!");
            $this->info("Check the email inbox for: {$shipment->client->email}");
            return 0;
        } catch (\Exception $e) {
            $this->error("\n✗ Failed to send email!");
            $this->error($e->getMessage());
            return 1;
        }
    }
}
