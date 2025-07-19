@include('layouts.app')
@include('layouts.header')

<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Complete Your Order - Jippy Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-10">

<div class="bg-white shadow-xl rounded-2xl p-8 max-w-lg mx-auto w-full text-center">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-orange-600">Place Your Order on Our App</h1>
        <p class="text-gray-600 mt-2 text-lg">
            To ensure smooth delivery and a better experience,<br/>
            please download and place your order using the Jippy Mart app.
        </p>
    </div>

    <div class="flex flex-col items-center gap-4">
        <img
            src="https://api.qrserver.com/v1/create-qr-code/?data=https://play.google.com/store/apps/details?id=com.jippymart.customer&size=200x200"
            alt="Download Jippy Mart App"
            class="w-48 h-48 border-2 border-gray-300 rounded-lg p-2"
        />
        <p class="text-gray-500 text-sm">Scan the QR code to download the app</p>

        <!-- Play Store Button -->
        <a
            href="https://play.google.com/store/apps/details?id=com.jippymart.customer"
            target="_blank"
            class="mt-3 inline-flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-full hover:bg-green-700 transition"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4v16m8-8H4"></path>
            </svg>
            Download on Play Store
        </a>
    </div>

    <div class="mt-6 text-sm text-gray-400">
        Already have the app? <span class="text-blue-500">Just open it and place your order!</span>
    </div>
</div>

</body>
</html>

@include('layouts.footer')
