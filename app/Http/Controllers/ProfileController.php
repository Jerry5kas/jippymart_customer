<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Services\FirebaseService;
use Carbon\Carbon;

class ProfileController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
        $this->middleware('auth');
    }

    /**
     * Show user profile page with Firebase sync
     */
    public function index()
    {
        $user = Auth::user();
        
        // Try to sync with Firebase to get latest data
        try {
            if ($user->phone) {
                \Log::info('Profile page: Fetching latest user data from Firebase', [
                    'user_id' => $user->id,
                    'phone' => substr($user->phone, -4)
                ]);
                
                $firebaseUser = $this->firebaseService->getUserByPhone($user->phone);
                
                if ($firebaseUser) {
                    \Log::info('Profile page: Firebase data found', [
                        'firstName' => $firebaseUser['firstName'] ?? 'N/A',
                        'lastName' => $firebaseUser['lastName'] ?? 'N/A',
                        'email' => $firebaseUser['email'] ?? 'N/A'
                    ]);
                    
                    // Trim trailing spaces from Firebase data
                    $firstName = trim($firebaseUser['firstName'] ?? '');
                    $lastName = trim($firebaseUser['lastName'] ?? '');
                    $fullName = trim($firstName . ' ' . $lastName);
                    $email = $firebaseUser['email'] ?? $user->email;
                    $profilePictureURL = $firebaseUser['profilePictureURL'] ?? null;
                    $firebaseId = $firebaseUser['id'] ?? null;
                    
                    // Update user record if we have new data
                    if ($fullName || $email) {
                        $updateData = [];
                        
                        if ($fullName) {
                            $updateData['name'] = $fullName;
                        }
                        if ($email && $email !== $user->phone . '@jippymart.in') {
                            $updateData['email'] = $email;
                        }
                        if ($firebaseId) {
                            $updateData['firebase_uid'] = $firebaseId;
                        }
                        
                        if (!empty($updateData)) {
                            $user->update($updateData);
                            \Log::info('Profile page: User record updated from Firebase');
                        }
                    }
                    
                    // Add Firebase data to user object for display
                    $user->firstName = $firstName;
                    $user->lastName = $lastName;
                    $user->profilePictureURL = $profilePictureURL;
                } else {
                    \Log::warning('Profile page: No Firebase data found for user', [
                        'phone' => $user->phone
                    ]);
                    
                    // Try to split existing name into first/last
                    $nameParts = explode(' ', $user->name, 2);
                    $user->firstName = $nameParts[0] ?? '';
                    $user->lastName = $nameParts[1] ?? '';
                    $user->profilePictureURL = null;
                }
            } else {
                // No phone number, use existing data
                $nameParts = explode(' ', $user->name, 2);
                $user->firstName = $nameParts[0] ?? '';
                $user->lastName = $nameParts[1] ?? '';
                $user->profilePictureURL = null;
            }
        } catch (\Exception $e) {
            \Log::error('Profile page: Firebase sync failed', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: use existing MySQL data
            $nameParts = explode(' ', $user->name, 2);
            $user->firstName = $nameParts[0] ?? '';
            $user->lastName = $nameParts[1] ?? '';
            $user->profilePictureURL = null;
        }
        
        return view('users.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $updateData = [];
            
            // Build full name from first and last name
            $firstName = $request->input('first_name', '');
            $lastName = $request->input('last_name', '');
            
            if ($firstName || $lastName) {
                $updateData['name'] = trim($firstName . ' ' . $lastName);
            }
            
            if ($request->has('email')) {
                $updateData['email'] = $request->input('email');
            }
            
            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $image = $request->file('profile_picture');
                $imageName = time() . '_' . $user->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/users'), $imageName);
                $updateData['profile_picture'] = '/images/users/' . $imageName;
            }
            
            if (!empty($updateData)) {
                $user->update($updateData);
                
                \Log::info('Profile updated', [
                    'user_id' => $user->id,
                    'fields' => array_keys($updateData)
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Profile update failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile'
            ], 500);
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }
        
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        \Log::info('Password changed', ['user_id' => $user->id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully!'
        ]);
    }

    /**
     * Get user data as JSON (for AJAX requests)
     */
    public function getUserData()
    {
        $user = Auth::user();
        
        // Try to get fresh data from Firebase
        try {
            if ($user->phone) {
                $firebaseUser = $this->firebaseService->getUserByPhone($user->phone);
                
                if ($firebaseUser) {
                    $firstName = trim($firebaseUser['firstName'] ?? '');
                    $lastName = trim($firebaseUser['lastName'] ?? '');
                    
                    return response()->json([
                        'success' => true,
                        'user' => [
                            'id' => $user->id,
                            'firstName' => $firstName,
                            'lastName' => $lastName,
                            'name' => trim($firstName . ' ' . $lastName) ?: $user->name,
                            'email' => $firebaseUser['email'] ?? $user->email,
                            'phone' => $user->phone,
                            'profilePictureURL' => $firebaseUser['profilePictureURL'] ?? null,
                            'wallet_amount' => $firebaseUser['wallet_amount'] ?? 0
                        ]
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Get user data failed', ['error' => $e->getMessage()]);
        }
        
        // Fallback to MySQL data
        $nameParts = explode(' ', $user->name, 2);
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'firstName' => $nameParts[0] ?? '',
                'lastName' => $nameParts[1] ?? '',
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profilePictureURL' => $user->profile_picture ?? null,
                'wallet_amount' => 0
            ]
        ]);
    }
}
