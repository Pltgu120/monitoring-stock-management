<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(): View
    {
        return view('admin.login');
    }

    public function auth(Request $request)
    {
        $credentials = $request->only('username', 'password');
    
        // Attempt to authenticate with the provided credentials
        if (!Auth::guard('web')->attempt($credentials)) {
            return redirect()->back()->withErrors([
                'error' => __("incorrect username or password") . "!"
            ]);
        }
    
        // Check the authenticated user's status
        $user = Auth::user();
        if ($user->status == 0) {
            Auth::logout(); // Log out the user if they have an inactive status
            return redirect()->back()->withErrors([
                'error' => __("Silahkan Hubungi Admin") . "!"
            ]);
        }
    
        // Redirect to dashboard
        return redirect()->route('dashboard');
    }
    
    

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
