<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $auth;

    public function __construct()
    {
        $serviceAccountPath = config('services.firebase.credentials');

        if (!file_exists($serviceAccountPath)) {
            throw new \Exception("Service account file not found: " . $serviceAccountPath);
        }

        $factory = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->withDatabaseUri(config('services.firebase.database_url'));

        $this->auth = $factory->createAuth();
    }

    public function getAuth()
    {
        return $this->auth;
    }
}
