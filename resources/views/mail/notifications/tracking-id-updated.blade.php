<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking ID Updated</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .info-card { background: #f9fafb; border-left: 4px solid #3b82f6; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #6b7280; font-size: 14px; }
        .info-value { color: #111827; font-weight: 500; font-size: 14px; text-align: right; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 600; font-family: 'Courier New', monospace; }
        .badge-old { background: #fee2e2; color: #991b1b; text-decoration: line-through; }
        .badge-new { background: #d1fae5; color: #065f46; }
        .highlight-box { background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 13px; color: #6b7280; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔄 Tracking ID Updated</h1>
        </div>

        <div class="content">
            <p>The tracking ID for shipment <strong>{{ $shipment->shipment_code }}</strong> has been updated by <strong>{{ $shipment->client->name }}</strong>.</p>

            <div class="highlight-box">
                <div style="margin-bottom: 15px;">
                    <div style="font-weight: 600; color: #6b7280; font-size: 14px; margin-bottom: 5px;">Previous Tracking ID:</div>
                    @if($oldTrackingId)
                        <span class="badge badge-old">{{ $oldTrackingId }}</span>
                    @else
                        <span style="color: #9ca3af; font-style: italic;">Not set</span>
                    @endif
                </div>
                <div>
                    <div style="font-weight: 600; color: #6b7280; font-size: 14px; margin-bottom: 5px;">New Tracking ID:</div>
                    <span class="badge badge-new">{{ $newTrackingId }}</span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Shipment Code:</span>
                    <span class="info-value"><strong>{{ $shipment->shipment_code }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Client:</span>
                    <span class="info-value">{{ $shipment->client->name }} (Group: {{ $shipment->client->group_id }})</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Source:</span>
                    <span class="info-value">{{ $shipment->source }}</span>
                </div>
                @if($shipment->delivery_partner)
                <div class="info-row">
                    <span class="info-label">Delivery Partner:</span>
                    <span class="info-value">{{ $shipment->delivery_partner }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">{{ str_replace('_', ' ', ucwords($shipment->status)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Updated:</span>
                    <span class="info-value">{{ now()->format('d M Y, h:i A') }}</span>
                </div>
            </div>

            @if($shipment->products->count() > 0)
            <h3 style="color: #111827; font-size: 16px; margin-top: 20px;">Products:</h3>
            <ul style="list-style: none; padding: 0;">
                @foreach($shipment->products as $product)
                <li style="padding: 10px; margin: 5px 0; background: #f3f4f6; border-radius: 4px; border-left: 3px solid #3b82f6;">
                    <strong>{{ $product->name }}</strong> - Qty: {{ $product->quantity_expected }}
                </li>
                @endforeach
            </ul>
            @endif

            <p style="margin-top: 30px; padding: 15px; background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
                <strong style="color: #1e40af;">Note:</strong> This update allows better tracking of the shipment through the delivery process.
            </p>
        </div>

        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong> - Warehouse Management</p>
            <p style="font-size: 12px; margin-top: 10px;">This is an automated notification.</p>
        </div>
    </div>
</body>
</html>
