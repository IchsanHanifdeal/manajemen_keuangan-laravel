<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required', // Validasi email
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $userRole = $user->role;

            $loginTime = Carbon::now();
            $request->session()->put([
                'login_time' => $loginTime->toDateTimeString(),
                'nama' => $user->nama,
                'id_user' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at
            ]);

            if (in_array($userRole, ['admin', 'user'])) {
                return redirect()->intended('dashboard')->with('success', 'Login successful!');
            }

            return back()->with('loginError', 'Login failed, user role not recognized.');
        }

        toastr()->error('Incorrect email or password.');

        return back()->withErrors([
            'loginError' => 'Incorrect email or password.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        toastr()->success('Logout berhasil, Anda telah keluar');

        return redirect('/');
    }
}
