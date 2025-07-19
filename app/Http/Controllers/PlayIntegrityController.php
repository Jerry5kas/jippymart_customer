<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlayIntegrityController extends Controller
{
public function verifyToken(Request $request)
{
try {
Log::info('Play Integrity verification started', [
    'request_data' => $request->all()
]);

$request->validate([
'token' => 'required|string',
'nonce' => 'required|string',
]);

$token = $request->input('token');
$nonce = $request->input('nonce');

// Skip verification for emulator testing
if ($token === 'EMULATOR_MODE') {
return response()->json([
'isValid' => true,
'message' => 'Emulator mode detected',
]);
}

Log::info('Getting access token');
$accessToken = $this->getAccessToken();
Log::info('Access token obtained successfully');

$packageName = env('GOOGLE_PLAY_PACKAGE_NAME');
Log::info('Package name from env', ['package_name' => $packageName]);

$url = "https://playintegrity.googleapis.com/v1/{$packageName}:decodeIntegrityToken";

Log::info('Making request to Play Integrity API', [
    'url' => $url
]);

$response = Http::withToken($accessToken)
->post($url, [
'integrity_token' => $token,
]);

if (!$response->successful()) {
Log::error('Play Integrity API error', [
'status' => $response->status(),
'body' => $response->body(),
'url' => $url,
'package_name' => $packageName
]);

return response()->json([
'isValid' => false,
'message' => 'Failed to verify integrity token: ' . $response->body(),
], 400);
}

$result = $response->json();
$payload = $result['tokenPayloadExternal'] ?? null;

if (!$payload || ($payload['nonce'] ?? '') !== $nonce) {
return response()->json([
'isValid' => false,
'message' => 'Invalid or missing nonce',
], 400);
}

$appIntegrity = $payload['appIntegrity'] ?? null;
$deviceIntegrity = $payload['deviceIntegrity'] ?? null;

if (!$appIntegrity || ($appIntegrity['appRecognitionVerdict'] ?? '') !== 'PLAY_RECOGNIZED') {
return response()->json([
'isValid' => false,
'message' => 'App integrity check failed',
], 400);
}

$deviceVerdict = $deviceIntegrity['deviceRecognitionVerdict'][0] ?? null;
if ($deviceVerdict !== 'MEETS_DEVICE_INTEGRITY') {
return response()->json([
'isValid' => false,
'message' => 'Device integrity check failed',
], 400);
}

return response()->json([
'isValid' => true,
'message' => 'Integrity verification successful',
]);
} catch (\Exception $e) {
Log::error('Play Integrity verification error', [
'error' => $e->getMessage(),
'trace' => $e->getTraceAsString(),
'line' => $e->getLine(),
'file' => $e->getFile(),
'request_data' => $request->all()
]);

return response()->json([
'isValid' => false,
'message' => 'Internal server error: ' . $e->getMessage(),
'details' => app()->environment('local') ? [
    'file' => $e->getFile(),
    'line' => $e->getLine()
] : null
], 500);
}
}

private function getAccessToken(): string
{
try {
    $credentialsPath = base_path(env('GOOGLE_SERVICE_ACCOUNT_PATH'));
    Log::info('Reading credentials from path', ['path' => $credentialsPath]);

    if (!file_exists($credentialsPath)) {
        throw new \Exception("Google service account credentials file not found at: {$credentialsPath}");
    }

    $credentials = json_decode(file_get_contents($credentialsPath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Failed to parse Google service account credentials: ' . json_last_error_msg());
    }

    $jwt = $this->generateJWT($credentials);

    $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt,
    ]);

    Log::info('Google response', [
        $response,
    ]);

    if (!$response->successful()) {

        Log::error('Google OAuth error', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        throw new \Exception('Failed to obtain access token from Google: ' . $response->body());
    }

    return $response->json()['access_token'];
} catch (\Exception $e) {
    Log::error('Error getting access token', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    throw $e;
}
}

private function generateJWT(array $credentials): string
{
$header = [
'alg' => 'RS256',
'typ' => 'JWT',
];

$now = time();
$payload = [
'iss' => $credentials['client_email'],
'scope' => 'https://www.googleapis.com/auth/playintegrity',
'aud' => 'https://oauth2.googleapis.com/token',
'exp' => $now + 3600,
'iat' => $now,
];

$base64Header = $this->base64UrlEncode(json_encode($header));
$base64Payload = $this->base64UrlEncode(json_encode($payload));

openssl_sign(
"$base64Header.$base64Payload",
$signature,
$credentials['private_key'],
OPENSSL_ALGO_SHA256
);

$base64Signature = $this->base64UrlEncode($signature);

return "$base64Header.$base64Payload.$base64Signature";
}

private function base64UrlEncode(string $data): string
{
return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
}
