<?php

namespace App\Services;

use Exception;

class FirebaseService
{
    protected array $credentials;

    public function __construct()
    {
        $path = storage_path(
            'app/firebase/mcckohalpur-firebase-adminsdk-fbsvc-567874c636.json'
        );

        if (! file_exists($path)) {
            throw new Exception(
                "Firebase service account file not found: {$path}"
            );
        }

        $this->credentials = json_decode(
            file_get_contents($path),
            true
        );

        if (! $this->credentials) {
            throw new Exception(
                'Invalid Firebase service account JSON.'
            );
        }
    }

    /**
     * Send notification to a single FCM device token.
     */
    public function send(
        string $token,
        string $title,
        string $body,
        array $data = []
    ): array {
        $accessToken = $this->getAccessToken();

        $projectId = $this->credentials['project_id'];

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $message = [
            'message' => [
                'token' => $token,

                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],

                'data' => $this->normalizeData($data),
            ],
        ];

        $response = $this->request(
            $url,
            $message,
            $accessToken
        );

        return $response;
    }

    /**
     * Get OAuth 2.0 access token using service account.
     */
    protected function getAccessToken(): string
    {
        $now = time();

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $claim = [
            'iss' => $this->credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $encodedHeader = $this->base64UrlEncode(
            json_encode($header)
        );

        $encodedClaim = $this->base64UrlEncode(
            json_encode($claim)
        );

        $unsignedJwt = $encodedHeader . '.' . $encodedClaim;

        $privateKey = openssl_pkey_get_private(
            $this->credentials['private_key']
        );

        if (! $privateKey) {
            throw new Exception(
                'Unable to load Firebase private key.'
            );
        }

        $signature = '';

        openssl_sign(
            $unsignedJwt,
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        $jwt = $unsignedJwt . '.' . $this->base64UrlEncode($signature);

        $response = $this->curl(
            'https://oauth2.googleapis.com/token',
            [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
            ]
        );

        if (
            empty($response['access_token'])
        ) {
            throw new Exception(
                'Unable to obtain Firebase access token: ' .
                json_encode($response)
            );
        }

        return $response['access_token'];
    }

    /**
     * Send HTTP request to FCM.
     */
    protected function request(
        string $url,
        array $payload,
        string $accessToken
    ): array {
        return $this->curl(
            $url,
            json_encode($payload),
            [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ],
            true
        );
    }

    /**
     * Generic cURL request.
     */
    protected function curl(
        string $url,
        $data,
        array $headers = [],
        bool $json = false
    ): array {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json
                ? $data
                : http_build_query($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);

            curl_close($ch);

            throw new Exception(
                'Firebase cURL error: ' . $error
            );
        }

        $statusCode = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        curl_close($ch);

        $decoded = json_decode(
            $response,
            true
        );

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new Exception(
                "Firebase API error ({$statusCode}): " .
                $response
            );
        }

        return $decoded ?? [];
    }

    /**
     * Base64 URL encoding.
     */
    protected function base64UrlEncode(string $data): string
    {
        return rtrim(
            strtr(
                base64_encode($data),
                '+/',
                '-_'
            ),
            '='
        );
    }

    /**
     * FCM data values must be strings.
     */
    protected function normalizeData(array $data): array
    {
        return collect($data)->map(
            fn ($value) => is_scalar($value)
                ? (string) $value
                : json_encode($value)
        )->toArray();
    }
}