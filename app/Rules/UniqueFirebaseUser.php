<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\FirebaseService;

class UniqueFirebaseUser implements Rule
{
    protected $firebaseAuth;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseAuth = $firebaseService->getAuth();
    }

    public function passes($attribute, $value)
    {
        try {
            // Try to fetch a user by email
            $this->firebaseAuth->getUserByEmail($value);
            return false; // User exists
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            return true; // User does not exist
        } catch (\Exception $e) {
            return false; // Any other error, consider it invalid
        }
    }

    public function message()
    {
        return 'The :attribute is already in use.';
    }
}
