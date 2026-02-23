<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unknown Shipment Created</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%); color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .info-card { background: #f9fafb; border-left: 4px solid #9333ea; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #6b7280; font-size: 14px; }
        .info-value { color: #111827; font-weight: 500; font-size: 14px; text-align: right; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 600; }
        .badge-purple { background: #ddd6fe; color: #6b21a8; }
        .badge-orange { background: #fed7aa; color: #92400e; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 13px; color: #6b7280; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 Unknown Shipment Created & Received</h1>
        </div>

        <div class="content">
            <p>An unknown shipment has been created and immediately received by <strong>{{ $creatorName }}</strong> ({{ ucfirst($creatorType) }}).</p>

            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Shipment Code:</span>
                    <span class="info-value"><strong>{{ $shipment->shipment_code }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tracking ID:</span>
                    <span class="info-value"><span class="badge badge-purple">{{ $shipment->tracking_id }}</span></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Created By:</span>
                    <span class="info-value">{{ $creatorName }} ({{ ucfirst($creatorType) }})</span>
                </div>
                @if($shipment->client)
                <div class="info-row">
                    <span class="info-label">Assigned Client:</span>
                    <span class="info-value">{{ $shipment->client->name }} ({{ $shipment->client->group_id }})</span>
                </div>
                @else
                <div class="info-row">
                    <span class="info-label">Client:</span>
                    <span class="info-value"><span class="badge badge-orange">Unassigned</span></span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Source:</span>
                    <span class="info-value">{{ $shipment->source }}</span>
                </div>
                @if($shipment->delivery_partner)
                <div class="info-row">
                    <span class="info-label">Delivery Partner:</span>
                    <span class="info-value"><span class="badge badge-green">{{ $shipment->delivery_partner }}</span></span>
                </div>
                @endif
                @if($shipment->category)
                <div class="info-row">
                    <span class="info-label">Category:</span>
                    <span class="info-value">{{ ucfirst($shipment->category) }}</span>
                </div>
                @endif
                @if($shipment->rack_location)
                <div class="info-row">
                    <span class="info-label">Rack Location:</span>
                    <span class="info-value"><strong>{{ $shipment->rack_location }}</strong></span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value"><span class="badge badge-green">Received</span></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Created:</span>
                    <span class="info-value">{{ $shipment->created_at->format('d M Y, h:i A') }}</span>
                </div>
            </div>

            @if($shipment->products->count() > 0)
            <h3 style="color: #111827; font-size: 16px; margin-top: 20px;">Products:</h3>
            <ul style="list-style: none; padding: 0;">
                @foreach($shipment->products as $product)
                <li style="padding: 10px; margin: 5px 0; background: #f3f4f6; border-radius: 4px; border-left: 3px solid #9333ea;">
                    <strong>{{ $product->name }}</strong> - Qty: {{ $product->quantity_expected }}
                    @if($product->description)<br><small style="color: #6b7280;">{{ $product->description }}</small>@endif
                </li>
                @endforeach
            </ul>
            @endif

            @if($shipment->product_description)
            <div style="margin-top: 15px; padding: 12px; background: #faf5ff; border-radius: 4px;">
                <strong style="color: #6b21a8;">Description:</strong>
                <p style="margin: 5px 0 0; color: #5b21b6;">{{ $shipment->product_description }}</p>
            </div>
            @endif
        </div>

        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong> - Warehouse Management</p>
            <p style="font-size: 12px; margin-top: 10px;">This is an automated notification.</p>
        </div>
    </div>
</body>
</html>
