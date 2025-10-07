<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact Form Message</title>
    <style>
        body{font-family:Arial,sans-serif;line-height:1.6;color:#333;max-width:600px;margin:0 auto;padding:20px}
        .header{background:#007bff;color:white;padding:20px;text-align:center}
        .content{padding:20px;background:#f8f9fa}
        .field{margin:10px 0}
        .label{font-weight:bold}
        .value{margin-left:10px}
        .footer{text-align:center;padding:20px;color:#666;font-size:12px}
    </style>
</head>
<body>
    <div class="header">
        <h2>New Contact Form Message</h2>
    </div>
    
    <div class="content">
        <div class="field"><span class="label">Name:</span><span class="value">{{ $data['name'] }}</span></div>
        <div class="field"><span class="label">Email:</span><span class="value">{{ $email }}</span></div>
        <div class="field"><span class="label">Message:</span><span class="value">{{ $data['message'] }}</span></div>
        <div class="field"><span class="label">Submitted:</span><span class="value">{{ now()->format('M j, Y g:i A') }}</span></div>
    </div>
    
    <div class="footer">
        <p>JippyMart Contact Form</p>
    </div>
</body>
</html>
