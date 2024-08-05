<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.pengguna', [
            'total_user' => User::Count(),
            'total_admin' => User::where('role', 'admin')->count(),
            'user' => User::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required',
            'role' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            toastr()->success('Pendaftaran pengguna Berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Pendaftaran gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
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
    public function update(Request $request, $id_user)
    {
        $request->validate([
            'up_nama' => 'required',
            'up_username' => 'required',
            'up_role' => 'required',
        ]);

        $user = User::findOrFail($id_user);

        $user->nama = $request->input('up_nama');
        $user->username = $request->input('up_username');
        $user->role = $request->input('up_role');
        $user->save();

        return redirect()->back()->with('success', 'User berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_user)
    {
        try {
            $user = User::findOrFail($id_user);

            $user->delete();

            toastr()->success('Pengguna berhasil dihapus!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Hapus pengguna gagal: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
