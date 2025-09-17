@include('auth.default')
<div class="login-page vh-100 bg-primary">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-6">
            <div class="col-10 mx-auto card p-3">
                <!-- Header -->
                <div class="text-center mb-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <a href="{{ route('otp.phone') }}" class="btn btn-link text-decoration-none p-0 me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h3 class="text-dark text-center font-weight-bold my-0">Verify Your Number</h3>
                        <i class="fas fa-mobile-alt text-primary ml-3"></i>
                    </div>
                    <p class="text-50">Enter the OTP sent to your mobile number.</p>
                </div>

                <!-- Phone Number Display -->
                <div class="text-center mb-4">
                    <p class="text-dark font-weight-bold">
                        +91 •••••••{{ substr(session('otp_phone'), -3) }}
                    </p>
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

                <!-- OTP Input Form -->
                <form class="mt-3 mb-4" method="POST" action="{{ route('otp.verify') }}" id="otpForm">
                    @csrf

                    <div class="form-group">
                        <div class="d-flex justify-content-center gap-2" id="otpInputs">
                            <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="0" required>
                            <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="1" required>
                            <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="2" required>
                            <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="3" required>
                            <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="4" required>
                            <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="5" required>
                        </div>
                        <input type="hidden" name="otp" id="otpValue">
                        @error('otp')
                            <div class="error text-center mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="verifyBtn" disabled>
                        Verify & Next
                    </button>
                </form>

                <!-- Resend OTP -->
                <div class="text-center">
                    <p class="text-muted">
                        Didn't receive any code?
                        <a href="{{ route('otp.resend') }}" class="text-decoration-none font-weight-bold" id="resendLink">
                            Resend in <span id="countdown">60</span>s
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpValue = document.getElementById('otpValue');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendLink = document.getElementById('resendLink');
    const countdownSpan = document.getElementById('countdown');

    let countdown = 60;
    let countdownInterval;

    // Start countdown
    function startCountdown() {
        countdownInterval = setInterval(() => {
            countdown--;
            countdownSpan.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(countdownInterval);
                resendLink.innerHTML = 'Resend OTP';
                resendLink.style.pointerEvents = 'auto';
            }
        }, 1000);
    }

    // Initialize countdown
    startCountdown();

    // Handle OTP input
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;

            // Only allow numbers
            if (!/^\d$/.test(value)) {
                e.target.value = '';
                return;
            }

            // Move to next input
            if (value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            updateOTPValue();
        });

        input.addEventListener('keydown', function(e) {
            // Handle backspace
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');

            if (pastedData.length === 6) {
                for (let i = 0; i < 6; i++) {
                    otpInputs[i].value = pastedData[i];
                }
                updateOTPValue();
                otpInputs[5].focus();
            }
        });
    });

    function updateOTPValue() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        otpValue.value = otp;

        // Enable/disable verify button
        verifyBtn.disabled = otp.length !== 6;

        // Auto-submit when all 6 digits are entered
        if (otp.length === 6) {
            setTimeout(() => {
                document.getElementById('otpForm').submit();
            }, 500);
        }
    }

    // Focus first input on load
    otpInputs[0].focus();
});
</script>

<style>
.otp-input {
    width: 45px;
    height: 45px;
    font-size: 1.2rem;
    font-weight: bold;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.3s ease;
    margin: 0 2px;
}

.otp-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.otp-input.filled {
    border-color: #28a745;
    background-color: #f8f9fa;
}

#resendLink {
    pointer-events: none;
    color: #6c757d !important;
}

#resendLink:not([style*="pointer-events: none"]) {
    color: #007bff !important;
    pointer-events: auto;
}
</style>
