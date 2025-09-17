@include('auth.default')
<div class="login-page vh-100 bg-primary">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-8">
            <div class="col-10 mx-auto card p-3">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h3 class="text-dark text-center font-weight-bold my-0 mb-3">Create an Account 🚀</h3>
                    <p class="text-50">Sign up to start your food adventure with JippyMart</p>
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

                <!-- Registration Form -->
                <form class="mt-3 mb-4" method="POST" action="{{ route('otp.register.complete') }}">
                    @csrf

                    <div class="row">
                        <!-- First Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="text-dark font-weight-bold">First Name</label>
                                <input
                                    type="text"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name"
                                    name="first_name"
                                    placeholder="Enter First Name"
                                    value="{{ old('first_name') }}"
                                    required
                                    autofocus
                                >
                                @error('first_name')
                                    <div class="error">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" class="text-dark font-weight-bold">Last Name</label>
                                <input
                                    type="text"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name"
                                    name="last_name"
                                    placeholder="Enter Last Name"
                                    value="{{ old('last_name') }}"
                                    required
                                >
                                @error('last_name')
                                    <div class="error">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="text-dark font-weight-bold">Email Address</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            placeholder="Enter Email Address"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Phone Number (Read-only) -->
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
                                class="form-control bg-light"
                                id="phone"
                                value="{{ $phone }}"
                                readonly
                            >
                        </div>
                        <small class="text-muted">Phone number is verified and cannot be changed</small>
                    </div>

                    <!-- Referral Code (Optional) -->
                    <div class="form-group">
                        <label for="referral_code" class="text-dark font-weight-bold">Referral Code (Optional)</label>
                        <input
                            type="text"
                            class="form-control @error('referral_code') is-invalid @enderror"
                            id="referral_code"
                            name="referral_code"
                            placeholder="Referral Code (Optional)"
                            value="{{ old('referral_code') }}"
                        >
                        @error('referral_code')
                            <div class="error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Signup
                    </button>
                </form>

                <!-- Terms and Privacy -->
                <div class="text-center">
                    <small class="text-muted">
                        By signing up, you agree to our
                        <a href="#" class="text-decoration-none">Terms of Service</a>
                        and
                        <a href="#" class="text-decoration-none">Privacy Policy</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

