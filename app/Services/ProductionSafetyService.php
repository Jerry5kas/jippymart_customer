<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductionSafetyService
{
    /**
     * Check if all required services are properly configured for production
     */
    public function checkProductionReadiness()
    {
        $issues = [];
        
        // Check Firebase configuration
        $firebaseIssues = $this->checkFirebaseConfiguration();
        $issues = array_merge($issues, $firebaseIssues);
        
        // Check email configuration
        $emailIssues = $this->checkEmailConfiguration();
        $issues = array_merge($issues, $emailIssues);
        
        // Check storage permissions
        $storageIssues = $this->checkStoragePermissions();
        $issues = array_merge($issues, $storageIssues);
        
        // Check queue configuration
        $queueIssues = $this->checkQueueConfiguration();
        $issues = array_merge($issues, $queueIssues);
        
        return [
            'ready' => empty($issues),
            'issues' => $issues
        ];
    }
    
    /**
     * Check Firebase configuration
     */
    private function checkFirebaseConfiguration()
    {
        $issues = [];
        
        // Check if Firebase credentials exist
        $credentialsPath = config('firebase.credentials');
        if ($credentialsPath && !file_exists($credentialsPath)) {
            $issues[] = "Firebase credentials file not found: {$credentialsPath}";
        }
        
        // Check environment variables
        $requiredEnvVars = [
            'FIREBASE_PROJECT_ID',
            'FIREBASE_PRIVATE_KEY',
            'FIREBASE_CLIENT_EMAIL'
        ];
        
        foreach ($requiredEnvVars as $var) {
            if (empty(env($var))) {
                $issues[] = "Missing required environment variable: {$var}";
            }
        }
        
        // Test Firebase connection
        try {
            $testService = new CateringService();
            // If constructor doesn't throw exception, Firebase is working
        } catch (\Exception $e) {
            $issues[] = "Firebase connection failed: " . $e->getMessage();
        }
        
        return $issues;
    }
    
    /**
     * Check email configuration
     */
    private function checkEmailConfiguration()
    {
        $issues = [];
        
        $requiredEmailVars = [
            'MAIL_HOST',
            'MAIL_USERNAME',
            'MAIL_PASSWORD'
        ];
        
        foreach ($requiredEmailVars as $var) {
            if (empty(env($var))) {
                $issues[] = "Missing email configuration: {$var}";
            }
        }
        
        return $issues;
    }
    
    /**
     * Check storage permissions
     */
    private function checkStoragePermissions()
    {
        $issues = [];
        
        $storagePaths = [
            'storage/app/firebase',
            'storage/logs'
        ];
        
        foreach ($storagePaths as $path) {
            $fullPath = base_path($path);
            
            if (!is_dir($fullPath)) {
                $issues[] = "Storage directory does not exist: {$path}";
                continue;
            }
            
            if (!is_writable($fullPath)) {
                $issues[] = "Storage directory not writable: {$path}";
            }
        }
        
        return $issues;
    }
    
    /**
     * Check queue configuration
     */
    private function checkQueueConfiguration()
    {
        $issues = [];
        
        $queueConnection = env('QUEUE_CONNECTION', 'sync');
        
        if ($queueConnection === 'sync') {
            $issues[] = "Queue is set to 'sync' - emails will be sent synchronously (slow response times)";
        }
        
        if ($queueConnection === 'database' && !$this->jobsTableExists()) {
            $issues[] = "Queue is set to 'database' but jobs table doesn't exist";
        }
        
        return $issues;
    }
    
    /**
     * Check if jobs table exists
     */
    private function jobsTableExists()
    {
        try {
            \DB::table('jobs')->limit(1)->get();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Get production deployment checklist
     */
    public function getDeploymentChecklist()
    {
        return [
            'environment_variables' => [
                'FIREBASE_PROJECT_ID' => 'Your Firebase project ID',
                'FIREBASE_PRIVATE_KEY' => 'Firebase service account private key',
                'FIREBASE_CLIENT_EMAIL' => 'Firebase service account email',
                'MAIL_HOST' => 'SMTP server host',
                'MAIL_USERNAME' => 'SMTP username',
                'MAIL_PASSWORD' => 'SMTP password',
                'QUEUE_CONNECTION' => 'Queue driver (database, redis, or sync)',
            ],
            'file_permissions' => [
                'storage/app/firebase/' => 'Must be writable by web server',
                'storage/logs/' => 'Must be writable by web server',
            ],
            'database_setup' => [
                'Run migrations' => 'php artisan migrate',
                'Create jobs table (if using database queue)' => 'php artisan queue:table && php artisan migrate',
            ],
            'firebase_setup' => [
                'Upload credentials file' => 'Place firebase credentials.json in storage/app/firebase/',
                'Set file permissions' => 'Ensure web server can read the credentials file',
            ]
        ];
    }
}
