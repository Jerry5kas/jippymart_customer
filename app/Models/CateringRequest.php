<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Firestore;
use Illuminate\Support\Facades\Log;

class CateringRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'place',
        'date',
        'guests',
        'function_type',
        'meal_preference',
        'veg_count',
        'nonveg_count',
        'special_requirements',
        'status',
        'reference_number',
        'ip_address',
        'user_agent'
    ];
    
    protected $casts = [
        'date' => 'date',
        'guests' => 'integer',
        'veg_count' => 'integer',
        'nonveg_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    protected $firestore;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        try {
            // Try multiple credential sources
            $credentials = $this->getFirebaseCredentials();
            
            if (is_string($credentials)) {
                // File path
                $factory = (new Factory)->withServiceAccount($credentials);
            } else {
                // Array credentials
                $factory = (new Factory)->withServiceAccount($credentials);
            }
            
            $this->firestore = $factory->createFirestore();
        } catch (\Exception $e) {
            Log::error('Firebase connection failed: ' . $e->getMessage());
            throw new \Exception('Firebase connection failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get Firebase credentials from multiple sources
     */
    private function getFirebaseCredentials()
    {
        // 1. Try file path from config
        $credentialsPath = config('firebase.credentials');
        if ($credentialsPath && file_exists($credentialsPath)) {
            return $credentialsPath;
        }
        
        // 2. Try environment variables
        $projectId = env('FIREBASE_PROJECT_ID');
        $privateKey = env('FIREBASE_PRIVATE_KEY');
        $clientEmail = env('FIREBASE_CLIENT_EMAIL');
        
        if ($projectId && $privateKey && $clientEmail) {
            return [
                'type' => 'service_account',
                'project_id' => $projectId,
                'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID', ''),
                'private_key' => str_replace('\\n', "\n", $privateKey),
                'client_email' => $clientEmail,
                'client_id' => env('FIREBASE_CLIENT_ID', ''),
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri' => 'https://oauth2.googleapis.com/token',
                'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL', ''),
            ];
        }
        
        // 3. Try default paths
        $defaultPaths = [
            storage_path('app/firebase/credentials.json'),
            storage_path('app/keys/credentials.json'),
            base_path('storage/app/firebase/credentials.json'),
        ];
        
        foreach ($defaultPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        throw new \Exception('Firebase credentials not found in any location');
    }
    
    /**
     * Store request in Firestore
     */
    public function storeInFirestore($data)
    {
        try {
            // Generate a unique ID for the document
            $docId = 'req_' . time() . '_' . rand(1000, 9999);
            
            $documentData = [
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'email' => $data['email'] ?? null,
                'alternative_mobile' => $data['alternative_mobile'] ?? null,
                'place' => $data['place'],
                'date' => $data['date'],
                'guests' => (int)$data['guests'],
                'function_type' => $data['function_type'],
                'meal_preference' => $data['meal_preference'],
                'veg_count' => isset($data['veg_count']) ? (int)$data['veg_count'] : null,
                'nonveg_count' => isset($data['nonveg_count']) ? (int)$data['nonveg_count'] : null,
                'special_requirements' => $data['special_requirements'] ?? null,
                'status' => 'pending',
                'reference_number' => $data['reference_number'],
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'admin_email_sent' => false,
                'customer_email_sent' => false
            ];
            
            // Use the correct Firebase API
            $database = $this->firestore->database();
            $collection = $database->collection('catering_requests');
            $docRef = $collection->document($docId);
            $docRef->set($documentData);
            
            return $docId;
        } catch (\Exception $e) {
            Log::error('Firestore store failed: ' . $e->getMessage());
            throw new \Exception('Failed to store request');
        }
    }
    
    /**
     * Get request from Firestore
     */
    public function getFromFirestore($id)
    {
        try {
            $database = $this->firestore->database();
            $collection = $database->collection('catering_requests');
            $docRef = $collection->document($id);
            $doc = $docRef->snapshot();
            
            if (!$doc->exists()) {
                return null;
            }
            
            return $doc->data();
        } catch (\Exception $e) {
            Log::error('Firestore get failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all requests with filters
     */
    public function getAllFromFirestore($filters = [])
    {
        try {
            $database = $this->firestore->database();
            $collection = $database->collection('catering_requests');
            $query = $collection;
            
            // Apply filters
            if (isset($filters['status'])) {
                $query = $query->where('status', '=', $filters['status']);
            }
            
            if (isset($filters['date_from'])) {
                $query = $query->where('date', '>=', $filters['date_from']);
            }
            
            if (isset($filters['date_to'])) {
                $query = $query->where('date', '<=', $filters['date_to']);
            }
            
            // Order by created_at
            $query = $query->orderBy('created_at', 'DESC');
            
            // Limit results
            $limit = $filters['per_page'] ?? 20;
            $query = $query->limit($limit);
            
            $docs = $query->documents();
            $results = [];
            
            foreach ($docs as $doc) {
                $results[] = $doc->data();
            }
            
            return $results;
        } catch (\Exception $e) {
            Log::error('Firestore get all failed: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update request status in Firestore
     */
    public function updateStatusInFirestore($id, $status)
    {
        try {
            $database = $this->firestore->database();
            $collection = $database->collection('catering_requests');
            $docRef = $collection->document($id);
            $docRef->update([
                ['path' => 'status', 'value' => $status],
                ['path' => 'updated_at', 'value' => now()->toISOString()]
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Firestore update failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate reference number
     */
    private function generateReferenceNumber()
    {
        return 'CAT-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Validation rules with comprehensive error messages
     */
    public static function validationRules()
    {
        return [
            // 1. Name - Required with proper message
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            
            // 2. Mobile - Indian mobile number format (6-9)
            'mobile' => [
                'required',
                'string',
                'regex:/^[6-9]\d{9}$/',
                'size:10'
            ],
            
            // 3. Email - Optional with valid format
            'email' => [
                'nullable',
                'email',
                'max:255'
            ],
            
            // 4. Alternative Mobile - Optional with Indian mobile format
            'alternative_mobile' => [
                'nullable',
                'string',
                'regex:/^[6-9]\d{9}$/',
                'size:10'
            ],
            
            // 4. Place/Address - Required with proper message
            'place' => [
                'required',
                'string',
                'max:1000',
                'min:10'
            ],
            
            // 5. Date - Required with proper format and future date
            'date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after:today',
                'before:+1 year'
            ],
            
            // 6. Guests - Required numeric, not zero
            'guests' => [
                'required',
                'integer',
                'min:1',
                'max:10000'
            ],
            
            // 7. Function Type - Required with flexible validation
            'function_type' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            
            // 8. Meal Preference - Required dropdown selection
            'meal_preference' => [
                'required',
                'string',
                'in:veg,non_veg,both'
            ],
            
            // 9. Veg Count - Only required when meal_preference is 'both'
            'veg_count' => [
                'nullable',
                'integer',
                'min:0',
                'max:10000'
            ],
            
            // 10. Non-Veg Count - Only required when meal_preference is 'both'
            'nonveg_count' => [
                'nullable',
                'integer',
                'min:0',
                'max:10000'
            ],
            
            // 11. Special Requirements - Optional description
            'special_requirements' => [
                'nullable',
                'string',
                'max:2000'
            ]
        ];
    }
    
    /**
     * Custom validation messages
     */
    public static function validationMessages()
    {
        return [
            // Name validation messages
            'name.required' => 'Name field is required. Please provide your full name.',
            'name.string' => 'Name must be a valid text.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'name.min' => 'Name must be at least 2 characters long.',
            'name.regex' => 'Name can only contain letters and spaces.',
            
            // Mobile validation messages
            'mobile.required' => 'Mobile number is required. Please provide your contact number.',
            'mobile.string' => 'Mobile number must be a valid text.',
            'mobile.regex' => 'Mobile number must be a valid Indian mobile number starting with 6, 7, 8, or 9.',
            'mobile.size' => 'Mobile number must be exactly 10 digits.',
            
            // Email validation messages
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            
            // Alternative Mobile validation messages
            'alternative_mobile.string' => 'Alternative mobile number must be a valid text.',
            'alternative_mobile.regex' => 'Alternative mobile number must be a valid Indian mobile number starting with 6, 7, 8, or 9.',
            'alternative_mobile.size' => 'Alternative mobile number must be exactly 10 digits.',
            
            // Place validation messages
            'place.required' => 'Place/Address is required. Please provide the event location.',
            'place.string' => 'Place/Address must be a valid text.',
            'place.max' => 'Place/Address cannot exceed 1000 characters.',
            'place.min' => 'Place/Address must be at least 10 characters long.',
            
            // Date validation messages
            'date.required' => 'Event date is required. Please select your event date.',
            'date.date' => 'Please provide a valid date.',
            'date.date_format' => 'Date must be in YYYY-MM-DD format.',
            'date.after' => 'Event date must be in the future.',
            'date.before' => 'Event date cannot be more than 1 year in advance.',
            
            // Guests validation messages
            'guests.required' => 'Number of guests is required. Please specify the guest count.',
            'guests.integer' => 'Number of guests must be a valid number.',
            'guests.min' => 'Number of guests must be at least 1.',
            'guests.max' => 'Number of guests cannot exceed 10,000.',
            
            // Function Type validation messages
            'function_type.required' => 'Function type is required. Please select the type of event.',
            'function_type.string' => 'Function type must be a valid text.',
            'function_type.in' => 'Please select a valid function type from the dropdown.',
            
            // Meal Preference validation messages
            'meal_preference.required' => 'Meal preference is required. Please select your meal preference.',
            'meal_preference.string' => 'Meal preference must be a valid text.',
            'meal_preference.in' => 'Please select a valid meal preference from the dropdown.',
            
            // Veg Count validation messages
            'veg_count.integer' => 'Vegetarian count must be a valid number.',
            'veg_count.min' => 'Vegetarian count cannot be negative.',
            'veg_count.max' => 'Vegetarian count cannot exceed 10,000.',
            
            // Non-Veg Count validation messages
            'nonveg_count.integer' => 'Non-vegetarian count must be a valid number.',
            'nonveg_count.min' => 'Non-vegetarian count cannot be negative.',
            'nonveg_count.max' => 'Non-vegetarian count cannot exceed 10,000.',
            
            // Special Requirements validation messages
            'special_requirements.string' => 'Special requirements must be a valid text.',
            'special_requirements.max' => 'Special requirements cannot exceed 2000 characters.'
        ];
    }
    
    /**
     * Custom validation for meal preference with detailed error messages
     */
    public static function validateMealPreference($data)
    {
        $errors = [];
        
        // If meal preference is 'both', both veg_count and nonveg_count are required
        if ($data['meal_preference'] === 'both') {
            // Check if veg_count is provided
            if (!isset($data['veg_count']) || $data['veg_count'] === null || $data['veg_count'] === '') {
                $errors['veg_count'] = ['Vegetarian count is required when meal preference is "Both".'];
            }
            
            // Check if nonveg_count is provided
            if (!isset($data['nonveg_count']) || $data['nonveg_count'] === null || $data['nonveg_count'] === '') {
                $errors['nonveg_count'] = ['Non-vegetarian count is required when meal preference is "Both".'];
            }
            
            // If both counts are provided, check if they sum to total guests
            if (isset($data['veg_count']) && isset($data['nonveg_count']) && 
                $data['veg_count'] !== null && $data['nonveg_count'] !== null) {
                
                $vegCount = (int)$data['veg_count'];
                $nonVegCount = (int)$data['nonveg_count'];
                $totalGuests = (int)$data['guests'];
                $sum = $vegCount + $nonVegCount;
                
                if ($sum !== $totalGuests) {
                    $errors['guests'] = [
                        "Guest count mismatch. Total guests: {$totalGuests}, but vegetarian ({$vegCount}) + non-vegetarian ({$nonVegCount}) = {$sum}. Please ensure the counts match."
                    ];
                }
            }
        }
        
        // If meal preference is 'veg', no veg_count or nonveg_count needed
        // Total guests count is sufficient
        
        // If meal preference is 'non_veg', no veg_count or nonveg_count needed  
        // Total guests count is sufficient
        
        return empty($errors) ? null : $errors;
    }
    
    /**
     * Get field order for API responses
     */
    public static function getFieldOrder()
    {
        return [
            'id',
            'name',
            'mobile',
            'email',
            'place',
            'date',
            'guests',
            'function_type',
            'meal_preference',
            'veg_count',
            'nonveg_count',
            'special_requirements',
            'status',
            'reference_number',
            'created_at',
            'updated_at'
        ];
    }
    
    /**
     * Get the last reference number from Firestore
     */
    public function getLastReferenceNumber()
    {
        try {
            if (!$this->firestore) {
                return null;
            }
            
            $database = $this->firestore->database();
            $collection = $database->collection('catering_requests');
            
            // Query for the last reference number
            $query = $collection->orderBy('reference_number', 'DESC')->limit(1);
            $documents = $query->documents();
            
            foreach ($documents as $document) {
                $data = $document->data();
                if (isset($data['reference_number'])) {
                    return $data['reference_number'];
                }
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Failed to get last reference number: ' . $e->getMessage());
            return null;
        }
    }
}
