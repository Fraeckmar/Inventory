<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\User;

class AuthController extends Controller
{

    // Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
        $log = new Log();
        $log->ip = $request->ip();
        if(!Auth::attempt($credentials)) {
            $log->action_type = 'login_unsuccessful';
            $log->save();
            return redirect('login')->withErrors('Username or Password is not correct!');
        }
        $log->action_type = 'login_successful';
        $log->user_id = Auth::id();
        $user = User::where('id', Auth::id())->get('name')->first();
        $log->user_name = !empty($user) ? $user->name : '';
        $log->save();
        return redirect('dashboard');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
