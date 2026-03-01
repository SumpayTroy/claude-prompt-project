<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| AUTH CONTROLLER
|--------------------------------------------------------------------------
| Handles showing the login form, processing login, and logging out.
|
| Think of it like your LoginActivity.java in Android —
| it handles the login button tap and validates credentials.
|--------------------------------------------------------------------------
*/
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN PAGE
    |--------------------------------------------------------------------------
    | Route: GET /
    | Just returns the login blade view.
    | If already logged in, redirect to dashboard.
    |--------------------------------------------------------------------------
    */
    public function showLogin()
    {
        // If already logged in, don't show login page again
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESS LOGIN
    |--------------------------------------------------------------------------
    | Route: POST /login
    | Validates the form input, attempts to log in, redirects on success.
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        // 1. Validate the incoming form data
        //    Like checking if EditText is empty in Android
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Attempt login with email + password
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // 3. Redirect to dashboard on success
            return redirect()->intended(route('dashboard'));
        }

        // 4. If login failed, go back with an error message
        //    withErrors() flashes error to the session (shown in blade with @error)
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    | Route: POST /logout
    | Clears the session and redirects to login.
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
