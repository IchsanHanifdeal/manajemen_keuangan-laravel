<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.kategori', [
            'total_kategori' => Kategori::Count(),
            'kategori_terbaru' => Kategori::latest()->first(),
            'kategori' => Kategori::all(),
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
            'kategori' => 'required|unique:kategori,kategori',
        ], [
            'kategori.unique' => 'Kategori sudah terdaftar, silahkan daftarkan kategori lain',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $kategori = Kategori::create([
                'kategori' => $request->kategori,
            ]);

            toastr()->success('Pendaftaran kategori Berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Pendaftaran gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(kategori $kategori)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_kategori)
    {
        $kategori = kategori::findOrFail($id_kategori);

        $validatedData = $request->validate([
            'kategori' => 'required|string|unique:kategori,kategori',
        ]);

        $kategori->kategori = $validatedData['kategori'];

        $kategori->update();

        if (!$kategori) {
            toastr()->error('gagal kategori merk!');
            return redirect()->back();
        }

        toastr()->success('Ubah kategori berhasil!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_kategori)
    {
        $kategori = Kategori::findOrFail($id_kategori);
        $kategori->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
}
