@include('layouts.app')

@include('layouts.header')
<script src="https://cdn.tailwindcss.com"></script>
<div class="container h-screen">
    <div class="my-16">
        <div class="max-w-2xl mx-auto px-4 py-10 text-gray-800">
            <h1 class="text-2xl font-bold mb-6">Jippy Mart Restaurant App – Data Deletion Request</h1>

            <p class="mb-4">
                To request deletion of your personal data <strong>without deleting your account</strong>:
            </p>

            <ol class="list-decimal list-inside mb-6 space-y-2">
                <li>
                    Email us at
                    <a href="mailto:support@jippy.in" class="text-blue-600 underline">support@jippymart.in</a>
                    with the subject <strong>"Data Deletion Request"</strong>.
                </li>
                <li>
                    Include your registered email or phone number used in the Restaurant app.
                </li>
            </ol>

            <p class="mb-4">We will:</p>
            <ul class="list-disc list-inside space-y-1 mb-6">
                <li>Delete your personal profile data, order history, saved addresses, and payment methods.</li>
                <li>Retain data required by law (e.g., billing records) for up to 90 days.</li>
            </ul>

            <p class="text-sm text-gray-500">Developer: Jippy Mart Technologies Pvt. Ltd.</p>
        </div>

    </div>
</div>

@include('layouts.footer')
