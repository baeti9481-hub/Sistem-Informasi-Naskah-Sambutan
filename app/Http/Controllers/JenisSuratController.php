<?php

namespace App\Http\Controllers;

use App\Models\Sambutan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JenisSuratController extends Controller
{
    public function sambutan(Request $request)
    {
        $judul = 'Sambutan';
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $hariIni = Carbon::now()->format('Y-m-d');

        $suratSambutan = Sambutan::whereIn('jenis_surat', ['Sambutan', 'Sambutan & Keprotokolan'])
            ->where('status', 'Publik')
            ->where('tanggal', '<=', $hariIni)
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

    public function keprotokolan(Request $request)
    {
        $judul = 'Keprotokolan';
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $hariIni = Carbon::now()->format('Y-m-d');

        $pengajuan = Sambutan::whereIn('jenis_surat', ['Keprotokolan', 'Sambutan & Keprotokolan'])
            ->where('status', 'Publik')
            ->where('tanggal', '<=', $hariIni)
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

    public function sambutanKeprotokolan(Request $request)
    {
        $judul = 'Sambutan & Keprotokolan';
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $hariIni = Carbon::now()->format('Y-m-d');

        $agenda = Sambutan::where('jenis_surat', 'Sambutan & Keprotokolan')
            ->where('status', 'Publik')
            ->where('tanggal', '<=', $hariIni)
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