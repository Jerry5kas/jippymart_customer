<?php

namespace App\Services;

use Google\Client;
use Google\Service\PlayIntegrity;

class PlayIntegrityService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/keys/service-account.json'));
        $this->client->addScope(PlayIntegrity::PLAYINTEGRITY);
    }

    public function verifyIntegrityToken($packageName, $token)
    {
        $service = new PlayIntegrity($this->client);

        $request = new PlayIntegrity\DecodeIntegrityTokenRequest();
        $request->setIntegrityToken($token);

        return $service->v1->decodeIntegrityToken($packageName, $request);
    }
}
