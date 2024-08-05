<?php

namespace App\Http\Controllers;

use App\Models\bank;
use App\Models\kategori;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.transaksi', [
            'transaksi_terbaru' => Transaksi::latest()->first(),
            'transaksi' => Transaksi::all(),
            'kategori' => kategori::all(),
            'rekening' => bank::all(),
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
            'transaksi_tanggal' => 'required|date',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'transaksi_keterangan' => 'required|string|max:255',
            'id_bank' => 'required|exists:bank,id_bank',
            'transaksi_jenis' => 'required|in:pemasukan,pengeluaran',
            'transaksi_nominal' => 'required|numeric|min:1',
        ], [
            'id_bank.exists' => 'Rekening tidak ditemukan, silahkan pilih rekening yang valid.',
            'transaksi_jenis.in' => 'Jenis transaksi tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::create([
                'transaksi_tanggal' => $request->transaksi_tanggal,
                'id_kategori' => $request->id_kategori,
                'transaksi_keterangan' => $request->transaksi_keterangan,
                'id_bank' => $request->id_bank,
                'transaksi_jenis' => $request->transaksi_jenis,
                'transaksi_nominal' => $request->transaksi_nominal,
            ]);

            $bank = Bank::find($request->input('id_bank'));

            if ($request->input('transaksi_jenis') == 'pengeluaran') {
                if ($request->input('transaksi_nominal') > $bank->saldo_bank) {
                    return redirect()->back()->withErrors(['transaksi_nominal' => 'Nominal transaksi tidak boleh lebih besar dari saldo bank.'])->withInput();
                }

                $bank->saldo_bank -= $request->input('transaksi_nominal');
            } else if ($request->input('transaksi_jenis') == 'pemasukan') {
                $bank->saldo_bank += $request->input('transaksi_nominal');
            }
            $bank->save();

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_transaksi)
    {
        $validator = Validator::make($request->all(), [
            'transaksi_tanggal' => 'required|date',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'transaksi_keterangan' => 'required|string|max:255',
            'id_bank' => 'required|exists:bank,id_bank',
            'transaksi_jenis' => 'required|in:pemasukan,pengeluaran',
            'transaksi_nominal' => 'required|numeric|min:1',
        ], [
            'id_bank.exists' => 'Rekening tidak ditemukan, silahkan pilih rekening yang valid.',
            'transaksi_jenis.in' => 'Jenis transaksi tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::findOrFail($id_transaksi);

            $bank = Bank::find($request->input('id_bank'));
            $originalBalance = $bank->saldo_bank;

            if ($request->input('transaksi_jenis') == 'pengeluaran') {
                if ($request->input('transaksi_nominal') > $originalBalance) {
                    return redirect()->back()->withErrors(['transaksi_nominal' => 'Nominal transaksi tidak boleh lebih besar dari saldo bank.'])->withInput();
                }

                $bank->saldo_bank = $originalBalance + $transaksi->transaksi_nominal - $request->input('transaksi_nominal');
            } else if ($request->input('transaksi_jenis') == 'pemasukan') {
                $bank->saldo_bank = $originalBalance - $transaksi->transaksi_nominal + $request->input('transaksi_nominal');
            }

            $bank->save();

            $transaksi->update([
                'transaksi_tanggal' => $request->transaksi_tanggal,
                'id_kategori' => $request->id_kategori,
                'transaksi_keterangan' => $request->transaksi_keterangan,
                'id_bank' => $request->id_bank,
                'transaksi_jenis' => $request->transaksi_jenis,
                'transaksi_nominal' => $request->transaksi_nominal,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui transaksi.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_transaksi)
    {
        $transaksi = Transaksi::findOrFail($id_transaksi);
        
        DB::beginTransaction();

        try {

            $bank = Bank::findOrFail($transaksi->id_bank);

            if ($transaksi->transaksi_jenis == 'pengeluaran') {
                if ($bank->saldo_bank < $transaksi->transaksi_nominal) {
                    throw new \Exception('Saldo bank tidak mencukupi untuk menghapus transaksi.');
                }

                $bank->saldo_bank += $transaksi->transaksi_nominal;
            } else if ($transaksi->transaksi_jenis == 'pemasukan') {
                $bank->saldo_bank -= $transaksi->transaksi_nominal;
            }

            $bank->save();

            $transaksi->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
