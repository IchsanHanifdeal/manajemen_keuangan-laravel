<?php

namespace App\Http\Controllers;

use App\Models\piutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.piutang', [
            'total_piutang' => piutang::sum('piutang_nominal'),
            'piutang_terbaru' => piutang::latest()->first(),
            'piutang' => piutang::all(),
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
            'piutang_tanggal' => 'required',
            'piutang_nominal' => 'required',
            'piutang_keterangan' => 'required',
        ]);

        try {
            $piutang = piutang::create([
                'piutang_tanggal' => $request->piutang_tanggal,
                'piutang_nominal' => $request->piutang_nominal,
                'piutang_keterangan' => $request->piutang_keterangan,
            ]);

            toastr()->success('Pendaftaran piutang Berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Pendaftaran gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(piutang $piutang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(piutang $piutang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_piutang)
    {
        $validator = Validator::make($request->all(), [
            'piutang_tanggal' => 'required',
            'piutang_nominal' => 'required',
            'piutang_keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $piutang = piutang::findOrFail($id_piutang);

            $piutang->update([
                'piutang_tanggal' => $request->piutang_tanggal,
                'piutang_nominal' => $request->piutang_nominal,
                'piutang_keterangan' => $request->piutang_keterangan,
            ]);

            toastr()->success('Update piutang Berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Update gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_piutang)
    {
        try {
            $piutang = piutang::findOrFail($id_piutang);

            $piutang->delete();

            toastr()->success('piutang berhasil dihapus!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Hapus piutang gagal: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
