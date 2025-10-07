<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
    <style>
        body{font-family:Arial,sans-serif;line-height:1.6;color:#333;max-width:600px;margin:0 auto;padding:20px}
        .header{background:#28a745;color:white;padding:20px;text-align:center}
        .content{padding:20px;background:#f8f9fa}
        .footer{text-align:center;padding:20px;color:#666;font-size:12px}
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $subject }}</h2>
    </div>
    
    <div class="content">
        <div style="white-space: pre-line;">{{ $message }}</div>
    </div>
    
    <div class="footer">
        <p>JippyMart Admin Email</p>
    </div>
</body>
</html>
