<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\OAuth2;

class PlayIntegrityController extends Controller
{
    private string $scope = 'https://www.googleapis.com/auth/playintegrity';
    
    /**
     * Validate and decode the Play Integrity token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateToken(Request $request)
    {
        try {
            // Validate request parameters
            $validator = Validator::make($request->all(), [
                'token' => 'required|string',
                'nonce' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get access token using Google Auth library
            $accessToken = $this->getAccessToken();
            
            if (!$accessToken) {
                Log::error('Failed to obtain access token from Google Auth');
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication failed'
                ], 500);
            }

            // Get project ID from service account
            $credentials = json_decode(file_get_contents(config('services.google.credentials_path')), true);
            $projectId = $credentials['project_id'];

            // Prepare Play Integrity API request
            $url = "https://playintegrity.googleapis.com/v1/projects/{$projectId}:decodeIntegrityToken";
            
            $response = Http::withToken($accessToken)
                ->post($url, [
                    'integrityToken' => $request->input('token')
                ]);

            if (!$response->successful()) {
                Log::error('Play Integrity API request failed', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to verify integrity token',
                    'error' => $response->json()
                ], $response->status());
            }

            $decodedToken = $response->json();
            
            // Verify the nonce matches
            if (!isset($decodedToken['tokenPayload']['nonce']) || 
                $decodedToken['tokenPayload']['nonce'] !== $request->input('nonce')) {
                Log::warning('Nonce mismatch in integrity token validation');
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid nonce'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $decodedToken
            ]);

        } catch (\Exception $e) {
            Log::error('Play Integrity validation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get Google OAuth access token using service account
     *
     * @return string|null
     */
    private function getAccessToken(): ?string
    {
        try {
            $credentialsPath = config('services.google.credentials_path');
            
            if (!file_exists($credentialsPath)) {
                Log::error('Google service account credentials file not found', [
                    'path' => $credentialsPath
                ]);
                return null;
            }

            // Create credentials object using Google Auth library
            $credentials = new ServiceAccountCredentials(
                $this->scope,
                $credentialsPath
            );

            // Request and return access token
            $token = $credentials->fetchAuthToken();
            return $token['access_token'] ?? null;

        } catch (\Exception $e) {
            Log::error('Failed to get access token', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}
