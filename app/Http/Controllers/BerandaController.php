<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sambutan;
use App\Models\Keprotokolan;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        // 1. Data Total User
        $totalUser = User::count();

        // 2. Perhitungan Otomatis Berdasarkan Tiga Jenis Surat di Tabel Sambutan
        $totalSuratSambutan     = Sambutan::where('jenis_surat', 'Sambutan')->count();
        $totalSuratKeprotokolan = Sambutan::where('jenis_surat', 'Keprotokolan')->count();
        $totalGabunganSurat     = Sambutan::where('jenis_surat', 'Sambutan & Keprotokolan')->count();
        
        // Total keseluruhan surat untuk card utama
        $totalSambutan          = $totalSuratSambutan + $totalSuratKeprotokolan + $totalGabunganSurat;

        $totalPengajuan         = Keprotokolan::count();
        $totalPendingProtokol   = 0;
        $totalDisetujuiProtokol = 0;
        $totalDitolakProtokol   = 0;

        // 3. Data Grafik Per Bulan
        $sambutanPerBulan = Sambutan::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereNotNull('tanggal')
        ->groupBy(DB::raw('MONTH(tanggal)'))
        ->pluck('total', 'bulan')
        ->toArray();

        $dataBulananSambutan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBulananSambutan[] = (int) ($sambutanPerBulan[$i] ?? 0);
        }

        // 4. Data Grafik Berdasarkan Penyusun (Hanya yang benar-benar ada namanya)
        $suratPerPenyusun = Sambutan::select('penyusun as nama_penyusun', DB::raw('count(id) as total'))
            ->whereNotNull('penyusun')
            ->where('penyusun', '!=', '')
            ->where('penyusun', '!=', '-')
            ->groupBy('penyusun')
            ->get();

        $labelPenyusun = $suratPerPenyusun->pluck('nama_penyusun')->toArray();
        $dataPenyusun  = $suratPerPenyusun->pluck('total')->toArray();

        return view('backend.v_beranda.index', [
            'judul'                  => 'Beranda',
            'sub'                    => 'Halaman Beranda',
            'totalUser'              => $totalUser,
            'totalPengajuan'         => $totalPengajuan,
            'totalSuratSambutan'     => $totalSuratSambutan,
            'totalSuratKeprotokolan' => $totalSuratKeprotokolan,
            'totalGabunganSurat'     => $totalGabunganSurat,
            'totalPendingProtokol'   => $totalPendingProtokol,
            'totalDisetujuiProtokol' => $totalDisetujuiProtokol,
            'totalDitolakProtokol'   => $totalDitolakProtokol,
            'totalSambutan'          => $totalSambutan,
            'dataBulananSambutan'    => $dataBulananSambutan,
            'labelPenyusun'          => $labelPenyusun,
            'dataPenyusun'           => $dataPenyusun
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