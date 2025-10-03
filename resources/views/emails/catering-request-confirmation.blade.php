<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catering Request Confirmation</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .next-steps {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            color: #155724;
        }
        .next-steps h3 {
            margin: 0 0 15px 0;
            color: #155724;
        }
        .next-steps ul {
            margin: 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
        .contact-info {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .contact-info h3 {
            margin: 0 0 15px 0;
            color: #1976d2;
        }
        .contact-item {
            margin: 8px 0;
            font-weight: 500;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .status-badge {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            display: inline-block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Catering Request Confirmed</h1>
            <p>Thank you for choosing JippyMart Catering Service!</p>
        </div>
        
        <div class="content">
            <div class="reference-number">
                Reference: CAT-{{ date('Y') }}-{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}
            </div>
            
            <div class="status-badge">
                Status: Pending Review
            </div>
            
            <div class="request-info">
                <h3 style="margin-top: 0; color: #495057;">📋 Your Request Summary</h3>
                
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
            
            <div class="next-steps">
                <h3>📞 What Happens Next?</h3>
                <ul>
                    <li>Our team will review your request within 24 hours</li>
                    <li>We'll contact you to discuss availability and pricing</li>
                    <li>We'll provide a detailed quote for your event</li>
                    <li>Once confirmed, we'll finalize all the details</li>
                </ul>
            </div>
            
            <div class="contact-info">
                <h3>📞 Contact Information</h3>
                <div class="contact-item">📞 Phone: +91-XXXX-XXXXXX</div>
                <div class="contact-item">📧 Email: jerry@jippymart.in</div>
                <div class="contact-item">🌐 Website: www.jippymart.in</div>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Thank you for choosing JippyMart!</strong></p>
            <p>We look forward to making your event memorable.</p>
            <p style="font-size: 12px; color: #adb5bd;">
                This is an automated confirmation. Please save this email for your records.
            </p>
        </div>
    </div>
</body>
</html>