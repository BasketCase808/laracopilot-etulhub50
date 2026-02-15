<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $user = User::where('email', $request->email)
            ->where('is_admin', true)
            ->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'admin_logged_in' => true,
                'admin_user_id' => $user->id,
                'admin_user_name' => $user->name,
                'admin_user_email' => $user->email
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }
        
        return back()->withErrors([
            'email' => 'Invalid admin credentials'
        ])->withInput($request->only('email'));
    }
    
    public function logout(Request $request)
    {
        session()->forget(['admin_logged_in', 'admin_user_id', 'admin_user_name', 'admin_user_email']);
        
        return redirect()->route('admin.login')
            ->with('success', 'Logged out successfully');
    }
}