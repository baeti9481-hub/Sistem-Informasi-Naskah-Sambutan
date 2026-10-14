<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sambutan;
use App\Models\Keprotokolan; // Import model Keprotokolan
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        // 1. Data Total User
        $totalUser = User::count();

        // 2. Data Statistik Keprotokolan
        $totalPengajuan = Keprotokolan::count();
        $totalPendingProtokol = 0;
        $totalDisetujuiProtokol = 0;
        $totalDitolakProtokol = 0;

        // 3. Data Total Sambutan
        $totalSambutan = Sambutan::count();

        // 4. Data Sambutan Per Bulan (Akumulasi Grafik)
        $sambutanPerBulan = Sambutan::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereNotNull('tanggal')
        ->groupBy(DB::raw('MONTH(tanggal)'))
        ->pluck('total', 'bulan')
        ->toArray();

        // Array 12 Bulan (Januari - Desember)
        $dataBulananSambutan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBulananSambutan[] = (int) ($sambutanPerBulan[$i] ?? 0);
        }

        return view('backend.v_beranda.index', [
            'judul'                  => 'Beranda',
            'sub'                    => 'Halaman Beranda',
            'totalUser'              => $totalUser,
            'totalPengajuan'         => $totalPengajuan,
            'totalPendingProtokol'   => $totalPendingProtokol,
            'totalDisetujuiProtokol' => $totalDisetujuiProtokol,
            'totalDitolakProtokol'   => $totalDitolakProtokol,
            'totalSambutan'          => $totalSambutan,
            'dataBulananSambutan'    => $dataBulananSambutan
        ]);
    }

    public function index()
    {
        $sambutan = Sambutan::orderBy('created_at', 'desc')->paginate(6);

        return view('frontend.v_beranda.index', [
            'judul'    => 'Halaman Beranda',
            'sambutan' => $sambutan,
        ]);
    }
}