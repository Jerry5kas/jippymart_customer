@include('auth.default')
<div class="login-page vh-100 bg-primary">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-6">
            <div class="col-10 mx-auto card p-3">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h3 class="text-dark text-center font-weight-bold my-0 mb-3">Welcome Back! 👋</h3>
                    <p class="text-50">Log in to continue enjoying delicious food delivered to your doorstep.</p>
                </div>

                <!-- Success/Error Messages -->
                <div class="error" id="error"></div>
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Phone Input Form -->
                <form class="mt-3 mb-4" method="POST" action="{{ route('otp.send') }}">
                    @csrf

                    <div class="form-group">
                        <label for="phone" class="text-dark font-weight-bold">Phone Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <img src="https://flagcdn.com/16x12/in.png" alt="India" class="me-1" style="width: 16px; height: 12px;">
                                    +91
                                </span>
                            </div>
                            <input
                                type="tel"
                                class="form-control @error('phone') is-invalid @enderror"
                                id="phone"
                                name="phone"
                                placeholder="Enter Phone Number"
                                value="{{ old('phone') }}"
                                maxlength="10"
                                pattern="[0-9]{10}"
                                required
                                autofocus
                            >
                        </div>
                        @error('phone')
                            <div class="error">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">We'll send you a verification code</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Send OTP
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Format phone number input
document.getElementById('phone').addEventListener('input', function(e) {
    // Remove any non-numeric characters
    let value = e.target.value.replace(/\D/g, '');

    // Limit to 10 digits
    if (value.length > 10) {
        value = value.substring(0, 10);
    }

    e.target.value = value;
});

// Auto-submit form when 10 digits are entered
document.getElementById('phone').addEventListener('input', function(e) {
    if (e.target.value.length === 10) {
        // Optional: Auto-submit after a short delay
        // setTimeout(() => {
        //     e.target.form.submit();
        // }, 500);
    }
});
</script>
