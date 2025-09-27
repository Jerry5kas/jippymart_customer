<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JippyMart - Opening App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
        }
        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }
        h1 {
            margin: 20px 0;
            font-size: 28px;
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .play-store-btn {
            background: #4CAF50;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            cursor: pointer;
            margin: 20px 0;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }
        .play-store-btn:hover {
            background: #45a049;
        }
        .fallback {
            margin-top: 20px;
            font-size: 14px;
            opacity: 0.8;
        }
        .status {
            margin: 10px 0;
            font-size: 16px;
            min-height: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🛒</div>
        <h1>Opening JippyMart App</h1>
        <div class="loading"></div>
        <div class="status" id="status">Redirecting to your product...</div>
        
        @if(isset($debug) && $debug)
            @if(isset($detectionInfo))
            <div style="background: rgba(255,255,255,0.1); padding: 10px; margin: 10px 0; border-radius: 5px; font-size: 12px;">
                <strong>Debug Info:</strong><br>
                Mobile: {{ $detectionInfo['isMobile'] ? 'Yes' : 'No' }}<br>
                Mobile App: {{ $detectionInfo['isMobileApp'] ? 'Yes' : 'No' }}<br>
                Deep Link: {{ $detectionInfo['isDeepLink'] ? 'Yes' : 'No' }}<br>
                Mobile Headers: {{ $detectionInfo['hasMobileHeaders'] ? 'Yes' : 'No' }}<br>
                User Agent: {{ substr($userAgent, 0, 50) }}...
            </div>
            @endif
        @endif
        
        <div id="app-install" style="display: none;">
            <h2>Install JippyMart App</h2>
            <p>Get the best shopping experience with our mobile app!</p>
            <a href="{{ config('app.play_store_url', 'https://play.google.com/store/apps/details?id=com.jippymart.customer') }}" 
               class="play-store-btn" 
               id="play-store-link">
                Install from Play Store
            </a>
        </div>
        
        <div class="fallback">
            <p>If the app doesn't open automatically, <a href="#" id="manual-link" style="color: #ffeb3b;">click here</a></p>
        </div>
        
        @if(isset($debug) && $debug)
        <div style="background: rgba(0,0,0,0.8); color: #fff; padding: 20px; margin-top: 20px; border-radius: 10px; font-size: 12px;">
            <h4>🐛 Debug Information</h4>
            <p><strong>Product ID:</strong> {{ $productId ?? 'N/A' }}</p>
            <p><strong>Link Type:</strong> {{ $linkType ?? 'product' }}</p>
            <p><strong>User Agent:</strong> {{ substr($userAgent ?? 'N/A', 0, 100) }}...</p>
            <p><strong>App Scheme:</strong> jippymart://{{ $linkType ?? 'product' }}/{{ $productId ?? '123' }}</p>
            <p><strong>Status:</strong> Debug mode enabled - showing deep link handler</p>
        </div>
        @endif
    </div>

    <script>
        // Enhanced Deep Link Handler with Better Reliability
        console.log('🚀 JippyMart Deep Link Handler Starting...');
        
        // Get the product ID from URL with multiple fallbacks
        function getProductId() {
            // Method 1: URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            let productId = urlParams.get('product') || urlParams.get('id');
            
            // Method 2: Path segments
            if (!productId) {
                const pathSegments = window.location.pathname.split('/').filter(segment => segment);
                productId = pathSegments[pathSegments.length - 1];
            }
            
            // Method 3: Hash fragment
            if (!productId && window.location.hash) {
                productId = window.location.hash.substring(1);
            }
            
            console.log('📱 Extracted Product ID:', productId);
            return productId || '123'; // Fallback to default
        }
        
        const productId = getProductId();
        const linkType = '{{ $linkType ?? "product" }}';
        const appScheme = `jippymart://${linkType}/${productId}`;
        const playStoreUrl = '{{ config("app.play_store_url", "https://play.google.com/store/apps/details?id=com.jippymart.customer") }}';
        
        let appOpened = false;
        let attempts = 0;
        const maxAttempts = 3;
        
        // Enhanced app opening with multiple methods
        function tryOpenApp() {
            attempts++;
            console.log(`🔄 Attempt ${attempts}/${maxAttempts} to open app...`);
            
            try {
                // Method 1: Direct window.location (works in most modern browsers)
                console.log('🌐 Trying window.location method...');
                window.location = appScheme;
                
                // Method 2: Iframe fallback (for older browsers)
                setTimeout(() => {
                    if (!appOpened) {
                        console.log('📱 Trying iframe method...');
                        const iframe = document.createElement('iframe');
                        iframe.style.display = 'none';
                        iframe.src = appScheme;
                        document.body.appendChild(iframe);
                        
                        // Clean up iframe after 1 second
                        setTimeout(() => {
                            if (iframe.parentNode) {
                                iframe.parentNode.removeChild(iframe);
                            }
                        }, 1000);
                    }
                }, 500);
                
                // Enhanced timeout with error handling
                setTimeout(() => {
                    if (!appOpened && attempts >= maxAttempts) {
                        console.log('⏰ App open timeout, showing install prompt');
                        showInstallPrompt();
                    }
                }, 2000);
                
            } catch (error) {
                console.error('❌ Error opening app:', error);
                showInstallPrompt();
            }
        }
        
        // Enhanced detection methods
        function setupAppDetection() {
            // Method 1: Visibility change (most reliable)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden && !appOpened) {
                    appOpened = true;
                    console.log('✅ App opened successfully (visibility change)');
                    updateStatus('App opened! Redirecting...');
                }
            });
            
            // Method 2: Page blur (backup)
            window.addEventListener('blur', () => {
                if (!appOpened) {
                    appOpened = true;
                    console.log('✅ App opened successfully (page blur)');
                    updateStatus('App opened! Redirecting...');
                }
            });
            
            // Method 3: Focus loss (backup)
            document.addEventListener('focusout', () => {
                if (!appOpened) {
                    appOpened = true;
                    console.log('✅ App opened successfully (focus loss)');
                    updateStatus('App opened! Redirecting...');
                }
            });
        }
        
        function updateStatus(message) {
            const statusEl = document.getElementById('status');
            if (statusEl) {
                statusEl.textContent = message;
            }
        }
        
        function showInstallPrompt() {
            if (appOpened) return; // Don't show if app already opened
            
            console.log('📱 App not installed, showing install prompt');
            document.getElementById('app-install').style.display = 'block';
            document.querySelector('.loading').style.display = 'none';
            updateStatus('App not installed. Please install from Play Store.');
        }
        
        // Handle manual retry
        document.getElementById('manual-link').addEventListener('click', (e) => {
            e.preventDefault();
            if (attempts < maxAttempts) {
                updateStatus('Retrying to open app...');
                tryOpenApp();
            } else {
                showInstallPrompt();
            }
        });
        
        // Handle Play Store installation
        document.getElementById('play-store-link').addEventListener('click', (e) => {
            console.log('📦 Storing pending deep link:', appScheme);
            
            // Store multiple formats for better compatibility
            localStorage.setItem('pendingDeepLink', appScheme);
            localStorage.setItem('pendingProductId', productId);
            localStorage.setItem('pendingDeepLinkTimestamp', Date.now().toString());
            
            // Also store in sessionStorage as backup
            sessionStorage.setItem('pendingDeepLink', appScheme);
            
            console.log('✅ Pending deep link stored successfully');
            updateStatus('Installing app... After installation, the app will open with your product!');
        });
        
        // Initialize
        console.log('🎯 Starting deep link process...');
        setupAppDetection();
        tryOpenApp();
        
        // Enhanced fallback with longer timeout
        setTimeout(() => {
            if (!appOpened && attempts < maxAttempts) {
                console.log('⏰ Timeout reached, retrying...');
                tryOpenApp();
            } else if (!appOpened) {
                console.log('❌ All attempts failed, showing install prompt');
                showInstallPrompt();
            }
        }, 4000); // Increased to 4 seconds
        
        // Final fallback
        setTimeout(() => {
            if (!appOpened) {
                showInstallPrompt();
            }
        }, 6000); // 6 seconds total
    </script>
</body>
</html>
