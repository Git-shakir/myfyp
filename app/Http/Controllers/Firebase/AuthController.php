<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\FirebaseService;


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
            $signInResult = $this->firebaseAuth->signInWithEmailAndPassword($request->email, $request->password);
            session(['firebase_user' => $signInResult->data()]);
            return redirect()->route('welcome')->with('status', 'Logged in successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Invalid credentials.');
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
            'password' => 'required|min:6',
        ]);

        try {
            $this->firebaseAuth->createUserWithEmailAndPassword($request->email, $request->password);
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
}
