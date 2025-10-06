<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Catering Request</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .request-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .field {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .field:last-child {
            border-bottom: none;
        }
        .field-label {
            font-weight: 600;
            color: #495057;
            flex: 1;
        }
        .field-value {
            color: #212529;
            flex: 2;
            text-align: right;
        }
        .reference-number {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 0 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .priority {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .priority h3 {
            margin: 0 0 10px 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍽️ New Catering Request</h1>
            <p>A new catering request has been submitted and requires your attention</p>
        </div>
        
        <div class="content">
            <div class="reference-number">
                Reference: {{ $data['reference_number'] }}
            </div>
            
            <div class="priority">
                <h3>⚠️ Action Required</h3>
                <p>Please review this request and contact the customer within 24 hours to confirm availability and provide a quote.</p>
            </div>
            
            <div class="request-info">
                <h3 style="margin-top: 0; color: #495057;">📋 Request Details</h3>
                
                <div class="field">
                    <span class="field-label">👤 Name:</span>
                    <span class="field-value">{{ $data['name'] }}</span>
                </div>
                
                <div class="field">
                    <span class="field-label">📱 Mobile:</span>
                    <span class="field-value">{{ $data['mobile'] }}</span>
                </div>
                
                @if(!empty($data['alternative_mobile']))
                <div class="field">
                    <span class="field-label">📱 Alternative Mobile:</span>
                    <span class="field-value">{{ $data['alternative_mobile'] }}</span>
                </div>
                @endif
                
                @if(!empty($data['email']))
                <div class="field">
                    <span class="field-label">📧 Email:</span>
                    <span class="field-value">{{ $data['email'] }}</span>
                </div>
                @endif
                
                <div class="field">
                    <span class="field-label">📍 Venue:</span>
                    <span class="field-value">{{ $data['place'] }}</span>
                </div>
                
                <div class="field">
                    <span class="field-label">📅 Event Date:</span>
                    <span class="field-value">{{ \Carbon\Carbon::parse($data['date'])->format('F j, Y') }}</span>
                </div>
                
                <div class="field">
                    <span class="field-label">👥 Total Guests:</span>
                    <span class="field-value">{{ $data['guests'] }} people</span>
                </div>
                
                <div class="field">
                    <span class="field-label">🎉 Event Type:</span>
                    <span class="field-value">{{ $data['function_type'] }}</span>
                </div>
                
                <div class="field">
                    <span class="field-label">🍽️ Meal Preference:</span>
                    <span class="field-value">
                        @if($data['meal_preference'] === 'veg')
                            Vegetarian Only
                        @elseif($data['meal_preference'] === 'non_veg')
                            Non-Vegetarian Only
                        @else
                            Both Vegetarian & Non-Vegetarian
                        @endif
                    </span>
                </div>
                
                @if($data['meal_preference'] === 'both')
                <div class="field">
                    <span class="field-label">🥬 Vegetarian:</span>
                    <span class="field-value">{{ $data['veg_count'] ?? 0 }} people</span>
                </div>
                
                <div class="field">
                    <span class="field-label">🥩 Non-Vegetarian:</span>
                    <span class="field-value">{{ $data['nonveg_count'] ?? 0 }} people</span>
                </div>
                @endif
                
                @if(!empty($data['special_requirements']))
                <div class="field">
                    <span class="field-label">⚠️ Special Requirements:</span>
                    <span class="field-value">{{ $data['special_requirements'] }}</span>
                </div>
                @endif
                
                <div class="field">
                    <span class="field-label">⏰ Submitted:</span>
                    <span class="field-value">{{ now()->format('F j, Y \a\t g:i A') }}</span>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="tel:{{ $data['mobile'] }}" class="btn">📞 Call Customer</a>
                <a href="mailto:{{ $data['email'] ?? '#' }}" class="btn">📧 Email Customer</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>JippyMart Catering Service</strong></p>
            <p>This is an automated notification. Please respond to the customer promptly.</p>
            <p style="font-size: 12px; color: #adb5bd;">
                Request ID: {{ $requestId ?? 'N/A' }} | Generated: {{ now()->format('Y-m-d H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>
