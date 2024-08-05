<?php

namespace App\Http\Controllers;

use App\Models\bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.rekening', [
            'total_bank' => bank::Count(),
            'bank_terbaru' => bank::latest()->first(),
            'bank' => bank::all(),
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
            'bank' => 'required',
            'nomor_rekening' => 'required|unique:bank,nomor_rekening',
            'pemilik' => 'required',
            'saldo_bank' => 'required',
        ], [
            'nomor_rekening.unique' => 'Rekening sudah terdaftar, silahkan daftarkan rekening lain',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $bank = bank::create([
                'nama_bank' => $request->bank,
                'nomor_rekening' => $request->nomor_rekening,
                'pemilik' => $request->pemilik,
                'saldo_bank' => $request->saldo_bank,
            ]);

            toastr()->success('Pendaftaran Rekening Berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Pendaftaran gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(bank $bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_bank)
    {
        $validator = Validator::make($request->all(), [
            'bank' => 'required',
            'nomor_rekening' => 'required|unique:bank,nomor_rekening,' . $id_bank . ',id_bank',
            'pemilik' => 'required',
            'saldo_bank' => 'required',
        ], [
            'nomor_rekening.unique' => 'Rekening sudah terdaftar, silahkan daftarkan rekening lain',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $bank = bank::findOrFail($id_bank);

            $bank->update([
                'nama_bank' => $request->bank,
                'nomor_rekening' => $request->nomor_rekening,
                'pemilik' => $request->pemilik,
                'saldo_bank' => $request->saldo_bank,
            ]);

            toastr()->success('Pembaruan Rekening Berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error('Pembaruan gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_bank)
    {
        $bank = bank::findOrFail($id_bank);
        $bank->delete();
        return redirect()->back()->with('success', 'Bank berhasil dihapus');
    }
}
