<?php

namespace App\Http\Controllers;

use App\Models\Sambutan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JenisSuratController extends Controller
{
    /**
     * Halaman Frontend Sambutan
     */
    public function sambutan(Request $request)
    {
        $judul = 'Sambutan';
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        // Ambil waktu saat ini (Tahun-Bulan-Hari Jam:Menit:Detik)
        $waktuSekarang = Carbon::now()->format('Y-m-d H:i:s');

        $suratSambutan = Sambutan::where('jenis_surat', 'Sambutan')
            ->where('status', 'Publik') // Harus berstatus Publik
            ->whereRaw("CONCAT(tanggal, ' ', waktu) <= ?", [$waktuSekarang]) // Otomatis terbit jika Waktu Acara <= Waktu Sekarang
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('acara', 'like', '%' . $search . '%')
                      ->orWhere('penyelenggara', 'like', '%' . $search . '%')
                      ->orWhere('penyusun', 'like', '%' . $search . '%')
                      ->orWhere('tempat', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        return view('frontend.v_sambutan.index', compact('judul', 'suratSambutan'));
    }

    /**
     * Halaman Frontend Keprotokolan
     */
    public function keprotokolan(Request $request)
    {
        $judul = 'Keprotokolan';
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $waktuSekarang = Carbon::now()->format('Y-m-d H:i:s');

        $pengajuan = Sambutan::where('jenis_surat', 'Keprotokolan')
            ->where('status', 'Publik')
            ->whereRaw("CONCAT(tanggal, ' ', waktu) <= ?", [$waktuSekarang])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('acara', 'like', '%' . $search . '%')
                      ->orWhere('penyelenggara', 'like', '%' . $search . '%')
                      ->orWhere('penyusun', 'like', '%' . $search . '%')
                      ->orWhere('tempat', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        return view('frontend.v_keprotokolan.index', compact('judul', 'pengajuan'));
    }

    /**
     * Halaman Frontend Sambutan & Keprotokolan
     */
    public function sambutanKeprotokolan(Request $request)
    {
        $judul = 'Sambutan & Keprotokolan';
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $waktuSekarang = Carbon::now()->format('Y-m-d H:i:s');

        $agenda = Sambutan::where('jenis_surat', 'Sambutan dan Keprotokolan')
            ->where('status', 'Publik')
            ->whereRaw("CONCAT(tanggal, ' ', waktu) <= ?", [$waktuSekarang])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('acara', 'like', '%' . $search . '%')
                      ->orWhere('penyelenggara', 'like', '%' . $search . '%')
                      ->orWhere('penyusun', 'like', '%' . $search . '%')
                      ->orWhere('tempat', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        $suratSambutan = $agenda;
        $pengajuan = $agenda;

        return view('frontend.v_sambutan_keprotokolan.index', compact('judul', 'agenda', 'suratSambutan', 'pengajuan'));
    }
}