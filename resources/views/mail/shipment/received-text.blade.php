{{ $businessName }}

✓ SHIPMENT RECEIVED
================================================================================

Dear {{ $client->name }},

Great news! Your shipment has been successfully received at our warehouse and is now being processed.

SHIPMENT DETAILS
--------------------------------------------------------------------------------
Shipment Code:    {{ $shipment->shipment_code }}
@if($shipment->tracking_id)
Tracking ID:      {{ $shipment->tracking_id }}
@endif
Status:           Received in Warehouse
Received At:      {{ $shipment->received_at ? $shipment->received_at->format('d M Y, h:i A') : 'Just now' }}
@if($shipment->delivery_partner)
Delivery Partner: {{ $shipment->delivery_partner }}
@endif
@if($shipment->source)
Source:           {{ $shipment->source }}
@endif
@if($shipment->category)
Category:         {{ ucfirst($shipment->category) }}
@endif
@if($shipment->rack_location)
Rack Location:    {{ $shipment->rack_location }}
@endif
@if($shipment->receivedByAgent)
Received By:      {{ $shipment->receivedByAgent->name }}
@endif
@if($shipment->warehouse_name)
Warehouse:        {{ $shipment->warehouse_name }}
@endif
@if($shipment->received_quantity)
Received Qty:     {{ $shipment->received_quantity }} units
@endif
@if($shipment->product_condition)
Condition:        {{ strtoupper($shipment->product_condition) }}
@endif

@if($shipment->products && $shipment->products->count() > 0)
PRODUCTS RECEIVED
--------------------------------------------------------------------------------
@foreach($shipment->products as $product)
• {{ $product->name }}
  Expected: {{ $product->quantity_expected }} | Available: {{ $product->quantity_available }}
@if($product->description)  Description: {{ $product->description }}@endif
@if($product->type_of_sale)  Type of Sale: {{ $product->type_of_sale }}@endif
@if($product->product_condition)  Condition: {{ strtoupper($product->product_condition) }}@endif
@if($product->link_url)  Link: {{ $product->link_url }}@endif
@if($product->notes)  Agent Notes: {{ $product->notes }}@endif

@endforeach
@endif

@if($shipment->scan1_notes)
WAREHOUSE NOTES
--------------------------------------------------------------------------------
{{ $shipment->scan1_notes }}

@endif
@if($shipment->attachments && $shipment->attachments->where('context', 'client_upload')->count())
CLIENT ATTACHMENTS
--------------------------------------------------------------------------------
@foreach($shipment->attachments->where('context', 'client_upload') as $attachment)
• {{ $attachment->original_name }}
  {{ url(Storage::url($attachment->file_path)) }}
@endforeach

@endif
@if($shipment->attachments && $shipment->attachments->where('context', 'scan1_proof')->count())
PROOF OF RECEIPT
--------------------------------------------------------------------------------
@foreach($shipment->attachments->where('context', 'scan1_proof') as $attachment)
• {{ $attachment->original_name }}
  {{ url(Storage::url($attachment->file_path)) }}
@endforeach

@endif
@if($shipment->lineItems && $shipment->lineItems->count())
LINE ITEMS (SCANNED BARCODES)
--------------------------------------------------------------------------------
@foreach($shipment->lineItems as $item)
• {{ $item->barcode }}
  Lookup: {{ $item->lookup_url }}
@endforeach

@endif
WHAT'S NEXT?
--------------------------------------------------------------------------------
Your inventory is now available for creating orders. You can track this shipment 
and create outbound orders from your dashboard.

View shipment details: {{ url('/client/shipments/' . $shipment->id) }}

================================================================================

Best regards,
{{ $businessName }} Team

Warehouse Management & Logistics

This is an automated notification. Please do not reply to this email.
