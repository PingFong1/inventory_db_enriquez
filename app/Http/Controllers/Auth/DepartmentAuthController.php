<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DeptUser;

class DepartmentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('department.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('department')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('department/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('department')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('department.login');
    }
}