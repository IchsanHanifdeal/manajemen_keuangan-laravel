<?php

namespace App\Http\Controllers;

use App\Models\hutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.hutang', [
            'total_hutang' => Hutang::sum('hutang_nominal'),
            'hutang_terbaru' => Hutang::latest()->first(),
            'hutang' => Hutang::all(),
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
            'hutang_tanggal' => 'required',
            'hutang_nominal' => 'required',
            'hutang_keterangan' => 'required',
        ]);

        try {
            $hutang = Hutang::create([
                'hutang_tanggal' => $request->hutang_tanggal,
                'hutang_nominal' => $request->hutang_nominal,
                'hutang_keterangan' => $request->hutang_keterangan,
            ]);

            toastr()->success('Pendaftaran hutang Berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Pendaftaran gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(hutang $hutang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(hutang $hutang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_hutang)
    {
        $validator = Validator::make($request->all(), [
            'hutang_tanggal' => 'required',
            'hutang_nominal' => 'required',
            'hutang_keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $hutang = Hutang::findOrFail($id_hutang);

            $hutang->update([
                'hutang_tanggal' => $request->hutang_tanggal,
                'hutang_nominal' => $request->hutang_nominal,
                'hutang_keterangan' => $request->hutang_keterangan,
            ]);

            toastr()->success('Update hutang Berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Update gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_hutang)
    {
        try {
            $hutang = Hutang::findOrFail($id_hutang);

            $hutang->delete();

            toastr()->success('Hutang berhasil dihapus!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Hapus hutang gagal: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
