<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Carbon::setLocale('Asia/Jakarta');
        $today = Carbon::today('Asia/Jakarta');
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfYear = $today->copy()->startOfYear();

        $transactions = Transaksi::where('transaksi_tanggal', '>=', $startOfYear)
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->transaksi_tanggal)->format('Y-m-d');
            });

        $data = [
            'pemasukan_hari_ini' => 0,
            'pengeluaran_hari_ini' => 0,
            'pemasukan_bulan_ini' => 0,
            'pengeluaran_bulan_ini' => 0,
            'pemasukan_tahun_ini' => 0,
            'pengeluaran_tahun_ini' => 0,
            'seluruh_pemasukan' => 0,
            'seluruh_pengeluaran' => 0,
        ];

        foreach ($transactions as $date => $dailyTransactions) {
            foreach ($dailyTransactions as $transaction) {
                $nominal = $transaction->transaksi_nominal;

                if ($transaction->transaksi_jenis == 'pemasukan') {
                    $data['seluruh_pemasukan'] += $nominal;
                } else if ($transaction->transaksi_jenis == 'pengeluaran') {
                    $data['seluruh_pengeluaran'] += $nominal;
                }

                if ($transaction->transaksi_tanggal >= $startOfYear) {
                    if ($transaction->transaksi_jenis == 'pemasukan') {
                        $data['pemasukan_tahun_ini'] += $nominal;
                    } else if ($transaction->transaksi_jenis == 'pengeluaran') {
                        $data['pengeluaran_tahun_ini'] += $nominal;
                    }
                }

                if ($transaction->transaksi_tanggal >= $startOfMonth) {
                    if ($transaction->transaksi_jenis == 'pemasukan') {
                        $data['pemasukan_bulan_ini'] += $nominal;
                    } else if ($transaction->transaksi_jenis == 'pengeluaran') {
                        $data['pengeluaran_bulan_ini'] += $nominal;
                    }
                }

                if ($transaction->transaksi_tanggal == $today->format('Y-m-d')) {
                    if ($transaction->transaksi_jenis == 'pemasukan') {
                        $data['pemasukan_hari_ini'] += $nominal;
                    } else if ($transaction->transaksi_jenis == 'pengeluaran') {
                        $data['pengeluaran_hari_ini'] += $nominal;
                    }
                }
            }
        }

        $monthlyData = Transaksi::select(
            DB::raw('MONTH(transaksi_tanggal) as month'),
            DB::raw('SUM(CASE WHEN transaksi_jenis = "pemasukan" THEN transaksi_nominal ELSE 0 END) as total_pemasukan'),
            DB::raw('SUM(CASE WHEN transaksi_jenis = "pengeluaran" THEN transaksi_nominal ELSE 0 END) as total_pengeluaran')
        )
        ->whereYear('transaksi_tanggal', $today->year)
        ->groupBy(DB::raw('MONTH(transaksi_tanggal)'))
        ->get()
        ->keyBy('month');
    
        $yearlyData = Transaksi::select(
            DB::raw('YEAR(transaksi_tanggal) as year'),
            DB::raw('SUM(CASE WHEN transaksi_jenis = "pemasukan" THEN transaksi_nominal ELSE 0 END) as total_pemasukan'),
            DB::raw('SUM(CASE WHEN transaksi_jenis = "pengeluaran" THEN transaksi_nominal ELSE 0 END) as total_pengeluaran')
        )
        ->groupBy(DB::raw('YEAR(transaksi_tanggal)'))
        ->get()
        ->keyBy('year');

        return view('dashboard.index', compact('data', 'monthlyData', 'yearlyData'));
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
    public function destroy(string $id)
    {
        //
    }
}
