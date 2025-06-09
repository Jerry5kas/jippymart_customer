@include('layouts.app')

@include('layouts.header')
<script src="https://cdn.tailwindcss.com"></script>
<div class="container h-screen">
    <div class="my-16">
        <div class="max-w-2xl mx-auto bg-white shadow-md rounded-2xl p-6 py-16 ">
            <h1 class="text-2xl font-bold mb-6">Jippy Mart Driver App – Data Deletion Request</h1>

            <p class="text-sm text-gray-600 mb-4">
                You can delete your account at any time. This will permanently remove all your data from our system, and
                you will no longer be able to access your profile or any saved information.
            </p>

            <div class="text-sm text-gray-700 mb-4">
                <p class="mb-1">To delete your account:</p>
                <ol class="list-decimal list-inside space-y-1 pl-4">
                    <li>select the <strong>Ham Burger Icon</strong> on the <strong>Top Left</strong>of the Home page.
                    </li>
                    <li>Go to <strong>Left Side Menu bar</strong></li>
                    <li>Scroll to the bottom of the Menu.</li>
                    <li>Click the <strong>Delete Account</strong> button.</li>
                    <li>Confirm the deletion when prompted.</li>
                </ol>
            </div>

            <ol class="list-decimal list-inside mb-6 space-y-2">
                <li>
                    For future enquiry email us at
                    <a href="mailto:support@jippy.in" class="text-blue-600 underline">support@jippymart.in</a>
                    with the subject <strong>"Driver Data Deletion Request"</strong>.
                </li>
                <li>
                    Include your registered email or phone number used in the Restaurant app.
                </li>
            </ol>

            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-md">
                <strong>Warning:</strong> This action is permanent and cannot be undone.
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
