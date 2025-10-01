<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Str;
use App\Services\FirebaseService;

class WebOtpController extends Controller
{
    protected $firebaseService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;

        // Skip location check for OTP authentication routes
        $otpRoutes = ['otp-login', 'otp-send', 'otp-verify', 'otp-register', 'otp-resend'];
        $currentPath = request()->path();
        
        // Only apply location check for web routes, not API routes or OTP routes
        if (!request()->is('api/*') && !in_array($currentPath, $otpRoutes) && !isset($_COOKIE['address_name'])) {
            \Redirect::to('set-location')->send();
        }
    }

    /**
     * Show phone input page (Step 1)
     */
    public function showPhoneInput()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.phone-input');
    }

    /**
     * Send OTP for web login (Step 1)
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $phone = $request->phone;

        // Check if there's a recent OTP request (rate limiting)
        $recentOtp = Otp::where('phone', $phone)
            ->where('created_at', '>', Carbon::now()->subMinutes(1))
            ->first();

        if ($recentOtp) {
            return back()->with('error', 'Please wait 1 minute before requesting another OTP');
        }

        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(10);

        \Log::info('Generating OTP', [
            'phone' => substr($phone, -4),
            'otp' => $otp, // Log OTP for development (remove in production)
            'expires_at' => $expiresAt
        ]);

        // Save OTP to database
        Otp::updateOrCreate(
            ['phone' => $phone],
            [
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'verified' => false,
                'attempts' => 0
            ]
        );

        // Send SMS using the existing SMS service
        $smsSent = $this->sendSms($phone, $otp);

        if ($smsSent) {
            // CRITICAL: Store phone in session with explicit save
            session()->put('otp_phone', $phone);
            session()->put('otp_sent_at', now());
            session()->save(); // Force session save
            
            \Log::info('OTP session created', [
                'phone' => substr($phone, -4),
                'session_id' => session()->getId(),
                'has_otp_phone' => session()->has('otp_phone')
            ]);
            
            return redirect()->route('otp.verify')->with('success', 'OTP sent to ' . substr($phone, 0, 2) . '******' . substr($phone, -2));
        } else {
            \Log::error('SMS sending failed', ['phone' => substr($phone, -4)]);
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    /**
     * Show OTP verification page (Step 2)
     */
    public function showOtpVerify()
    {
        // Check if user is already logged in
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // CRITICAL: Check if OTP was sent (session must exist)
        if (!session()->has('otp_phone')) {
            \Log::warning('OTP Verify accessed without session', [
                'session_id' => session()->getId(),
                'has_otp_phone' => session()->has('otp_phone'),
                'url' => request()->fullUrl()
            ]);
            
            return redirect()->route('otp.phone')->with('error', 'Please enter your phone number first.');
        }

        // Check if OTP session is not too old (30 minutes)
        $otpSentAt = session('otp_sent_at');
        if ($otpSentAt && now()->diffInMinutes($otpSentAt) > 30) {
            session()->forget(['otp_phone', 'otp_sent_at']);
            return redirect()->route('otp.phone')->with('error', 'OTP expired. Please request a new one.');
        }

        $phone = session('otp_phone');
        
        \Log::info('OTP Verify page loaded', [
            'phone' => substr($phone, -4),
            'session_id' => session()->getId()
        ]);

        return view('auth.verify-otp', ['phone' => $phone]);
    }

    /**
     * Verify OTP for web login (Step 2)
     */
    public function verifyOtp(Request $request)
    {
        \Log::info('OTP Verification attempt', [
            'has_otp_in_request' => $request->has('otp'),
            'otp_length' => strlen($request->otp ?? ''),
            'session_id' => session()->getId(),
            'has_session_phone' => session()->has('otp_phone'),
            'session_phone' => session('otp_phone') ? substr(session('otp_phone'), -4) : 'N/A',
            'request_method' => $request->method(),
            'csrf_token' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing'
        ]);

        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            \Log::warning('OTP validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        $phone = session('otp_phone');
        $otp = $request->otp;

        if (!$phone) {
            \Log::error('No phone in session during OTP verify', [
                'session_id' => session()->getId(),
                'all_session_data' => session()->all()
            ]);
            return redirect()->route('otp.phone')->with('error', 'Session expired. Please try again.');
        }

        \Log::info('Attempting OTP verification', [
            'phone' => substr($phone, -4),
            'otp' => $otp
        ]);

        // Find the OTP record
        $otpRecord = Otp::where('phone', $phone)
            ->where('otp', $otp)
            ->where('expires_at', '>', Carbon::now())
            ->where('verified', false)
            ->first();

        if (!$otpRecord) {
            // Increment attempts for failed verification
            Otp::where('phone', $phone)
                ->where('verified', false)
                ->increment('attempts');

            return back()->with('error', 'Invalid or expired OTP');
        }

        // Check if too many attempts
        if ($otpRecord->attempts >= 5) {
            return back()->with('error', 'Too many failed attempts. Please request a new OTP.');
        }

        // Mark OTP as verified
        $otpRecord->markAsVerified();

        // ALWAYS check Firebase first to get latest user data
        try {
            \Log::info('Checking Firebase for user', ['phone' => $phone]);
            
            $firebaseUser = $this->firebaseService->getUserByPhone($phone);

            if ($firebaseUser) {
                \Log::info('Firebase user found', [
                    'id' => $firebaseUser['id'] ?? 'N/A',
                    'firstName' => $firebaseUser['firstName'] ?? 'N/A',
                    'lastName' => $firebaseUser['lastName'] ?? 'N/A',
                    'email' => $firebaseUser['email'] ?? 'N/A',
                    'phoneNumber' => $firebaseUser['phoneNumber'] ?? 'N/A'
                ]);
                
                // Trim trailing spaces from Firebase data (Android format has trailing spaces)
                $firstName = trim($firebaseUser['firstName'] ?? '');
                $lastName = trim($firebaseUser['lastName'] ?? '');
                $fullName = trim($firstName . ' ' . $lastName);
                $email = $firebaseUser['email'] ?? $phone . '@jippymart.in';
                $firebaseId = $firebaseUser['id'] ?? null;

                // Check if user exists in Laravel database
                $user = User::where('phone', $phone)->first();

                if (!$user) {
                    \Log::info('Creating NEW Laravel user from Firebase data');
                    
                    // Create new Laravel user from Firebase data
                    $user = User::create([
                        'name' => $fullName ?: 'User_' . substr($phone, -4),
                        'phone' => $phone,
                        'email' => $email,
                        'password' => bcrypt(Str::random(16)),
                        'email_verified_at' => Carbon::now(),
                        'firebase_uid' => $firebaseId,
                    ]);
                    
                    \Log::info('Laravel user created', [
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'firebase_uid' => $user->firebase_uid
                    ]);
                } else {
                    \Log::info('Laravel user exists, UPDATING from Firebase data');
                    
                    // UPDATE existing user with latest Firebase data
                    // Only update fields that exist in the users table
                    $user->update([
                        'name' => $fullName ?: $user->name,
                        'email' => $email ?: $user->email,
                        'firebase_uid' => $firebaseId ?: $user->firebase_uid,
                    ]);
                    
                    \Log::info('Laravel user updated from Firebase', [
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'firebase_uid' => $user->firebase_uid
                    ]);
                }

                // Log the user in
                Auth::login($user, true);
                
                \Log::info('User logged in successfully', [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]);

                // Clear OTP session data
                session()->forget(['otp_phone', 'otp_sent_at']);

                return redirect()->route('home')->with('success', 'Welcome back, ' . $user->name . '!');
            } else {
                \Log::info('No Firebase user found - checking MySQL fallback');
                
                // Fallback: Check if user exists in MySQL only
                $user = User::where('phone', $phone)->first();
                
                if ($user) {
                    \Log::info('MySQL user found (no Firebase)', ['user_id' => $user->id]);
                    Auth::login($user, true);
                    session()->forget(['otp_phone', 'otp_sent_at']);
                    return redirect()->route('home')->with('success', 'Welcome back, ' . $user->name . '!');
                } else {
                    \Log::info('New user - redirecting to registration');
                    // User doesn't exist anywhere - redirect to registration form
                    return redirect()->route('otp.register')->with('success', 'OTP verified! Please complete your registration.');
                }
            }
        } catch (\Exception $e) {
            \Log::error('Firebase query failed: ' . $e->getMessage(), [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            // Fallback: Check MySQL
            $user = User::where('phone', $phone)->first();
            
            if ($user) {
                Auth::login($user, true);
                session()->forget(['otp_phone', 'otp_sent_at']);
                return redirect()->route('home')->with('success', 'Welcome back, ' . $user->name . '!');
            } else {
                return redirect()->route('otp.register')->with('success', 'OTP verified! Please complete your registration.');
            }
        }
    }

    /**
     * Show registration form for new users (Step 3)
     */
    public function showRegistration()
    {
        if (!session('otp_phone')) {
            return redirect()->route('otp.phone')->with('error', 'Session expired. Please request OTP again.');
        }

        // Check if OTP session is not too old (30 minutes)
        $otpSentAt = session('otp_sent_at');
        if ($otpSentAt && now()->diffInMinutes($otpSentAt) > 30) {
            session()->forget(['otp_phone', 'otp_sent_at']);
            return redirect()->route('otp.phone')->with('error', 'OTP session expired. Please request a new OTP.');
        }

        if (Auth::check()) {
            return redirect()->route('home');
        }

        $phone = session('otp_phone');
        return view('auth.otp-register', compact('phone'));
    }

    /**
     * Complete registration for new users (Step 3)
     */
    public function completeRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'referral_code' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $phone = session('otp_phone');
        if (!$phone) {
            return redirect()->route('otp.phone')->with('error', 'Session expired. Please try again.');
        }

        // Check if OTP session is not too old (30 minutes)
        $otpSentAt = session('otp_sent_at');
        if ($otpSentAt && now()->diffInMinutes($otpSentAt) > 30) {
            session()->forget(['otp_phone', 'otp_sent_at']);
            return redirect()->route('otp.phone')->with('error', 'OTP session expired. Please request a new OTP.');
        }

        try {
            // Generate UUID for new user
            $uuid = 'user_' . uniqid();

            // Create user in Firebase first - matching Android record structure exactly
            $firebaseUserData = [
                'id' => $uuid,
                'firstName' => $request->first_name . ' ', // Add trailing space like Android
                'lastName' => $request->last_name . ' ', // Add trailing space like Android
                'phoneNumber' => $phone,
                'email' => $request->email,
                'countryCode' => '+91',
                'active' => true,
                'isActive' => false, // Match Android format (false, not true)
                'isDocumentVerify' => false,
                'appIdentifier' => 'web',
                'provider' => 'email', // Match Android format (email, not phone)
                'role' => 'customer',
                'profilePictureURL' => null, // Add null field like Android
                'wallet_amount' => 0,
                'createdAt' => now(), // Keep timestamp format
                'shippingAddress' => [],
                'zoneId' => null // Add null field like Android
                // Note: fcmToken is intentionaly omitted for web users
            ];

            // Create user in Firebase
            $firebaseUser = $this->firebaseService->createUser($firebaseUserData);

            if (!$firebaseUser) {
                throw new \Exception('Failed to create user in Firebase');
            }

            // Create user in Laravel database
            $user = User::create([
                'name' => trim($request->first_name) . ' ' . trim($request->last_name),
                'first_name' => trim($request->first_name),
                'last_name' => trim($request->last_name),
                'phone' => $phone,
                'email' => $request->email,
                'password' => bcrypt(Str::random(16)), // Generate random password
                'email_verified_at' => Carbon::now(),
                'referral_code' => $request->referral_code,
                'firebase_uid' => $uuid,
            ]);

            // Log the user in
            Auth::login($user, true);

            // Clear OTP session data
            session()->forget(['otp_phone', 'otp_sent_at']);

            return redirect()->route('home')->with('success', 'Registration completed successfully! Welcome to JippyMart!');

        } catch (\Exception $e) {
            \Log::error('User registration failed: ' . $e->getMessage());

            // Fallback: Create user only in Laravel database
            $user = User::create([
                'name' => trim($request->first_name) . ' ' . trim($request->last_name),
                'first_name' => trim($request->first_name),
                'last_name' => trim($request->last_name),
                'phone' => $phone,
                'email' => $request->email,
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => Carbon::now(),
                'referral_code' => $request->referral_code,
            ]);

            Auth::login($user, true);
            session()->forget(['otp_phone', 'otp_sent_at']);

            return redirect()->route('home')->with('success', 'Registration completed successfully! Welcome to JippyMart!');
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp()
    {
        $phone = session('otp_phone');

        if (!$phone) {
            return redirect()->route('otp.phone')->with('error', 'Session expired. Please try again.');
        }

        // Delete existing OTP for this phone
        Otp::where('phone', $phone)->delete();

        // Create new request object
        $newRequest = new Request(['phone' => $phone]);

        // Call sendOtp method
        return $this->sendOtp($newRequest);
    }

    /**
     * Send SMS using the existing SMS service from OTPController
     */
    private function sendSms($phone, $otp)
    {
        // SMS API Configuration (same as OTPController)
        $smsApiUrl = 'https://restapi.smscountry.com/v0.1/Accounts/g3NwQZX8qbjHARPZktFZ/SMSes/';
        $authKey = 'Basic ZzNOd1FaWDhxYmpIQVJQWmt0Rlo6Y2lXdzBZRHUzbTFRY3hkMEFBSmZXaHNmczQ4TXRXdEs4Sk91TnR0Zg==';
        $senderId = 'JIPPYM';

        $smsText = "Your OTP for jippymart login is {$otp}. Please do not share this OTP with anyone. It is valid for the next 10 minutes-jippymart.in.";

        $payload = [
            "Text" => $smsText,
            "Number" => $phone,
            "SenderId" => $senderId,
            "DRNotifyUrl" => config('app.url') . "/api/sms-delivery-status",
            "DRNotifyHttpMethod" => "POST",
            "Tool" => "API"
        ];

        // Try cURL method (most reliable)
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $smsApiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $authKey,
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            return $httpCode >= 200 && $httpCode < 300;
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
