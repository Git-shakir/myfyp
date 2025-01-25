<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\FirebaseService;
use Kreait\Firebase\Exception\Authh;


class AuthController extends Controller
{

    protected $firebaseAuth;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseAuth = $firebaseService->getAuth();
    }

    public function showLoginForm()
    {
        return view('authentication.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // Step 1: Check if the email exists
            $userRecord = $this->firebaseAuth->getUserByEmail($request->email);

            // Step 2: Attempt to log in
            try {
                $signInResult = $this->firebaseAuth->signInWithEmailAndPassword($request->email, $request->password);
                $firebaseUser = $this->firebaseAuth->getUser($signInResult->firebaseUserId());

                // Store user data in the session
                session([
                    'firebase_user' => [
                        'email' => $firebaseUser->email,
                        'displayName' => $firebaseUser->displayName ?? 'N/A',
                    ]
                ]);

                return redirect()->route('welcome')->with('status', 'Logged in successfully!');
            } catch (\Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
                // Check for INVALID_LOGIN_CREDENTIALS specifically
                if (str_contains($e->getMessage(), 'INVALID_LOGIN_CREDENTIALS')) {
                    return back()->with('error', 'Incorrect password. Please try again.');
                }

                return back()->with('error', 'Login failed. Please check your credentials.');
            }
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            return back()->with('error', 'The email address is not registered.');
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }


    public function showRegisterForm()
    {
        return view('authentication.register');
    }

    public function register(Request $request, FirebaseService $firebaseService)
    {
        $request->validate([
            'email' => ['required', 'email', new \App\Rules\UniqueFirebaseUser($firebaseService)],
            'password' => 'required|min:6|confirmed',
            'name' => 'required|string|max:255',
        ]);

        try {
            // Step 1: Create the user with email and password
            $userProperties = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            $createdUser = $this->firebaseAuth->createUser($userProperties);

            // Step 2: Update the display name
            $this->firebaseAuth->updateUser($createdUser->uid, [
                'displayName' => $request->name,
            ]);

            return redirect('/login')->with('status', 'Registration successful! Please log in.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to register: ' . $e->getMessage());
        }
    }


    public function logout()
    {
        session()->forget('firebase_user');
        return redirect('/login')->with('status', 'Logged out successfully!');
    }

    public function profile()
    {
        $user = session('firebase_user');
        if (!$user) {
            return redirect()->route('loginpage')->with('error', 'You must be logged in to view this page.');
        }

        return view('authentication.profile');
    }

    public function showForgotPasswordForm()
    {
        return view('authentication.forgot-password');
    }

    public function sendResetPasswordEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // Step 1: Check if the email exists in Firebase
            $userRecord = $this->firebaseAuth->getUserByEmail($request->email);

            // Step 2: Send the password reset email
            $this->firebaseAuth->sendPasswordResetLink($request->email);

            return back()->with('status', 'Password reset email sent. Please check your inbox.');
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            // Email not registered
            return back()->with('error', 'The email address is not registered.');
        } catch (\Exception $e) {
            // General error handler
            return back()->with('error', 'An error occurred while sending the reset email. Please try again.');
        }
    }
}
