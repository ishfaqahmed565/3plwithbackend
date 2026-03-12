<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Received</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
        }
        .info-card {
            background-color: #f9fafb;
            border-left: 4px solid #9333ea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
        }
        .info-value {
            color: #111827;
            font-weight: 500;
            font-size: 14px;
            text-align: right;
        }
        .shipment-code {
            background-color: #9333ea;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: bold;
            display: inline-block;
        }
        .tracking-id {
            background-color: #ddd6fe;
            color: #6b21a8;
            padding: 6px 12px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            display: inline-block;
        }
        .status-badge {
            background-color: #d1fae5;
            color: #065f46;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }
        .product-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #faf5ff;
            border-radius: 6px;
        }
        .product-title {
            font-size: 16px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 10px;
        }
        .product-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .product-item {
            padding: 12px;
            margin: 8px 0;
            background-color: #ffffff;
            border-radius: 4px;
            border: 1px solid #e9d5ff;
        }
        .product-name {
            font-weight: 600;
            color: #7c3aed;
            font-size: 15px;
        }
        .product-details {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #9333ea;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #7c3aed;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
        }
        .highlight {
            color: #9333ea;
            font-weight: 600;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 20px;
            }
            .info-row {
                flex-direction: column;
            }
            .info-value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Shipment Received</h1>
            <p>{{ $businessName }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Dear {{ $client->name }},</p>
            
            <p>Great news! Your shipment has been successfully received at our warehouse and is now being processed.</p>

            <!-- Shipment Info Card -->
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Shipment Code:</span>
                    <span class="info-value"><span class="shipment-code">{{ $shipment->shipment_code }}</span></span>
                </div>
                
                @if($shipment->tracking_id)
                <div class="info-row">
                    <span class="info-label">Tracking ID:</span>
                    <span class="info-value"><span class="tracking-id">{{ $shipment->tracking_id }}</span></span>
                </div>
                @endif

                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value"><span class="status-badge">Received in Warehouse</span></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Received At:</span>
                    <span class="info-value">{{ $shipment->received_at ? $shipment->received_at->format('d M Y, h:i A') : 'Just now' }}</span>
                </div>

                @if($shipment->delivery_partner)
                <div class="info-row">
                    <span class="info-label">Delivery Partner:</span>
                    <span class="info-value">{{ $shipment->delivery_partner }}</span>
                </div>
                @endif

                @if($shipment->source)
                <div class="info-row">
                    <span class="info-label">Source:</span>
                    <span class="info-value">{{ $shipment->source }}</span>
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

                @if($shipment->receivedByAgent)
                <div class="info-row">
                    <span class="info-label">Received By Agent:</span>
                    <span class="info-value">{{ $shipment->receivedByAgent->name }}</span>
                </div>
                @endif

                @if($shipment->warehouse_name)
                <div class="info-row">
                    <span class="info-label">Warehouse Location:</span>
                    <span class="info-value">{{ $shipment->warehouse_name }}</span>
                </div>
                @endif

                @if($shipment->received_quantity)
                <div class="info-row">
                    <span class="info-label">Received Quantity:</span>
                    <span class="info-value"><strong>{{ $shipment->received_quantity }} units</strong></span>
                </div>
                @endif

                @if($shipment->product_condition)
                <div class="info-row">
                    <span class="info-label">Product Condition:</span>
                    <span class="info-value">
                        <span style="background-color: 
                            @if($shipment->product_condition === 'excellent') #d1fae5
                            @elseif($shipment->product_condition === 'good') #dbeafe
                            @elseif($shipment->product_condition === 'fair') #fef3c7
                            @elseif($shipment->product_condition === 'damaged') #fee2e2
                            @endif; color:
                            @if($shipment->product_condition === 'excellent') #065f46
                            @elseif($shipment->product_condition === 'good') #1e40af
                            @elseif($shipment->product_condition === 'fair') #92400e
                            @elseif($shipment->product_condition === 'damaged') #991b1b
                            @endif; padding: 4px 12px; border-radius: 4px; font-size: 13px; font-weight: 600; text-transform: uppercase;">
                            {{ $shipment->product_condition }}
                        </span>
                    </span>
                </div>
                @endif
            </div>

            <!-- Products Section -->
            @if($shipment->products && $shipment->products->count() > 0)
            <div class="product-section">
                <div class="product-title">📦 Products Received:</div>
                <ul class="product-list">
                    @foreach($shipment->products as $product)
                    <li class="product-item">
                        @if($product->image_path)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ url(Storage::url($product->image_path)) }}" alt="{{ $product->name }}" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #e9d5ff;">
                        </div>
                        @endif
                        
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-details">
                            Quantity: <strong>{{ $product->quantity_expected }}</strong> (Available: <strong style="color: #059669;">{{ $product->quantity_available }}</strong>)
                            @if($product->description)
                                <br>{{ $product->description }}
                            @endif
                            @if($product->type_of_sale)
                                <br>Type: <span class="highlight">{{ $product->type_of_sale }}</span>
                            @endif
                            @if($product->product_condition)
                                <br>Condition: <span style="background-color: 
                                    @if($product->product_condition === 'excellent') #d1fae5
                                    @elseif($product->product_condition === 'good') #dbeafe
                                    @elseif($product->product_condition === 'fair') #fef3c7
                                    @elseif($product->product_condition === 'damaged') #fee2e2
                                    @endif; color:
                                    @if($product->product_condition === 'excellent') #065f46
                                    @elseif($product->product_condition === 'good') #1e40af
                                    @elseif($product->product_condition === 'fair') #92400e
                                    @elseif($product->product_condition === 'damaged') #991b1b
                                    @endif; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase;">{{ $product->product_condition }}</span>
                            @endif
                        </div>
                        @if($product->link_url)
                        <div style="margin-top: 8px;">
                            <a href="{{ $product->link_url }}" style="color: #2563eb; text-decoration: underline; font-size: 12px; word-break: break-all;">{{ $product->link_url }}</a>
                        </div>
                        @endif
                        @if($product->notes)
                        <div style="margin-top: 10px; padding: 10px; background-color: #eff6ff; border-left: 3px solid #3b82f6; border-radius: 4px;">
                            <strong style="color: #1e40af; font-size: 12px;">Agent Notes:</strong>
                            <p style="margin: 5px 0 0; color: #1e3a8a; font-size: 12px;">{{ $product->notes }}</p>
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Notes Section -->
            @if($shipment->scan1_notes)
            <div style="margin: 20px 0; padding: 15px; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <strong style="color: #92400e;">Warehouse Notes:</strong>
                <p style="margin: 5px 0 0; color: #78350f;">{{ $shipment->scan1_notes }}</p>
            </div>
            @endif

            <!-- Client Attachments Section -->
            @if($shipment->attachments && $shipment->attachments->where('context', 'client_upload')->count())
            <div style="margin: 20px 0; padding: 15px; background-color: #f0fdf4; border-left: 4px solid #22c55e; border-radius: 4px;">
                <strong style="color: #166534; font-size: 16px; display: block; margin-bottom: 10px;">📎 Client Attachments</strong>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">
                    @foreach($shipment->attachments->where('context', 'client_upload') as $attachment)
                    <div style="text-align: center;">
                        <a href="{{ url(Storage::url($attachment->file_path)) }}" style="display: block; padding: 10px; background: white; border: 2px solid #bbf7d0; border-radius: 8px; text-decoration: none;">
                            @if(str_starts_with($attachment->mime_type, 'image/'))
                                <img src="{{ url(Storage::url($attachment->file_path)) }}" alt="Attachment" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px; margin-bottom: 5px;">
                            @else
                                <div style="width: 100%; height: 100px; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 4px; margin-bottom: 5px;">
                                    <span style="font-size: 24px;">📄</span>
                                </div>
                            @endif
                            <p style="margin: 0; font-size: 11px; color: #166534; word-break: break-all;">{{ $attachment->original_name }}</p>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Proof of Receipt Section -->
            @if($shipment->attachments && $shipment->attachments->where('context', 'scan1_proof')->count())
            <div style="margin: 20px 0; padding: 15px; background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
                <strong style="color: #1e40af; font-size: 16px; display: block; margin-bottom: 10px;">✓ Proof of Receipt</strong>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">
                    @foreach($shipment->attachments->where('context', 'scan1_proof') as $attachment)
                    <div style="text-align: center;">
                        <a href="{{ url(Storage::url($attachment->file_path)) }}" style="display: block; padding: 10px; background: white; border: 2px solid #bfdbfe; border-radius: 8px; text-decoration: none;">
                            @if(str_starts_with($attachment->mime_type, 'image/'))
                                <img src="{{ url(Storage::url($attachment->file_path)) }}" alt="Proof" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px; margin-bottom: 5px;">
                            @else
                                <div style="width: 100%; height: 100px; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 4px; margin-bottom: 5px;">
                                    <span style="font-size: 24px;">📄</span>
                                </div>
                            @endif
                            <p style="margin: 0; font-size: 11px; color: #1e40af; word-break: break-all;">{{ $attachment->original_name }}</p>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Line Items Section -->
            @if($shipment->lineItems && $shipment->lineItems->count())
            <div style="margin: 20px 0; padding: 15px; background-color: #faf5ff; border-left: 4px solid #9333ea; border-radius: 4px;">
                <strong style="color: #6b21a8; font-size: 16px; display: block; margin-bottom: 10px;">📊 Line Items (Scanned Barcodes)</strong>
                <div style="background: white; border-radius: 6px; overflow: hidden;">
                    @foreach($shipment->lineItems as $item)
                    <div style="padding: 10px; border-bottom: 1px solid #e9d5ff; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-family: 'Courier New', monospace; font-size: 13px; color: #333;">{{ $item->barcode }}</span>
                        <a href="{{ $item->lookup_url }}" style="color: #9333ea; text-decoration: none; font-size: 12px; font-weight: 600;">View Lookup</a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Call to Action -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/client/shipments/' . $shipment->id) }}" class="button">View Shipment Details</a>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                Your inventory is now available for creating orders. You can track this shipment and create outbound orders from your dashboard.
            </p>

            <p style="margin-top: 20px;">
                Best regards,<br>
                <strong>{{ $businessName }} Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $businessName }}</strong></p>
            <p>Warehouse Management & Logistics</p>
            <p style="margin-top: 10px; font-size: 12px;">
                This is an automated notification. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
