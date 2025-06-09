@include('layouts.app')

@include('layouts.header')
<script src="https://cdn.tailwindcss.com"></script>
<div class="container h-screen">
    <div class="my-16">
        <div class="max-w-2xl mx-auto bg-white shadow-md rounded-2xl p-6 py-16 ">
            <h2 class="text-sm text-gray-500 uppercase mb-2">Account Settings</h2>
            <h1 class="text-xl font-semibold text-red-600 mb-4">Delete Your Account</h1>

            <p class="text-sm text-gray-600 mb-4">
                You can delete your account at any time. This will permanently remove all your data from our system, and
                you will no longer be able to access your profile or any saved information.
            </p>

            <div class="text-sm text-gray-700 mb-4">
                <p class="mb-1">To delete your account:</p>
                <ol class="list-decimal list-inside space-y-1 pl-4">
                    <li>Go to <strong>Bottom Navigation bar</strong> from the <strong>Home</strong> page.</li>
                    <li>Click on <strong>Profile Information</strong>.</li>
                    <li>Scroll to the bottom of the page.</li>
                    <li>Click the <strong>Delete Account</strong> button.</li>
                    <li>Confirm the deletion when prompted.</li>
                </ol>
            </div>

            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-md">
                <strong>Warning:</strong> This action is permanent and cannot be undone.
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
