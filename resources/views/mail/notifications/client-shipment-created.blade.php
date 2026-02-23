<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Shipment Created</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .info-card { background: #f9fafb; border-left: 4px solid #10b981; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #6b7280; font-size: 14px; }
        .info-value { color: #111827; font-weight: 500; font-size: 14px; text-align: right; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 600; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 13px; color: #6b7280; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 New Shipment Created by Client</h1>
        </div>

        <div class="content">
            <p>A new shipment has been created by <strong>{{ $shipment->client->name }}</strong> and is waiting to be received.</p>

            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Shipment Code:</span>
                    <span class="info-value"><strong>{{ $shipment->shipment_code }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Client:</span>
                    <span class="info-value">{{ $shipment->client->name }} (Group: {{ $shipment->client->group_id }})</span>
                </div>
                @if($shipment->tracking_id)
                <div class="info-row">
                    <span class="info-label">Tracking ID:</span>
                    <span class="info-value"><span class="badge badge-blue">{{ $shipment->tracking_id }}</span></span>
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
                <div class="info-row">
                    <span class="info-label">Product Count:</span>
                    <span class="info-value"><strong>{{ $shipment->products->count() }} items</strong></span>
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
                <li style="padding: 10px; margin: 5px 0; background: #f3f4f6; border-radius: 4px; border-left: 3px solid #10b981;">
                    <strong>{{ $product->name }}</strong> - Qty: {{ $product->quantity_expected }}
                    @if($product->description)<br><small style="color: #6b7280;">{{ $product->description }}</small>@endif
                </li>
                @endforeach
            </ul>
            @endif

            <p style="margin-top: 30px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <strong style="color: #92400e;">Action Required:</strong> This shipment needs to be received at the warehouse (Scan-1).
            </p>
        </div>

        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong> - Warehouse Management</p>
            <p style="font-size: 12px; margin-top: 10px;">This is an automated notification.</p>
        </div>
    </div>
</body>
</html>
