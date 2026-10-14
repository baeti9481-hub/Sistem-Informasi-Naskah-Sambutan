<?php

namespace App\Http\Controllers;

use App\Models\Sambutan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SambutanController extends Controller
{
    // --- METHOD BACKEND (ADMIN) ---

    public function index(Request $request)
    {
        $query = Sambutan::query();

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('acara', 'like', '%' . $search . '%')
                  ->orWhere('penyelenggara', 'like', '%' . $search . '%')
                  ->orWhere('tempat', 'like', '%' . $search . '%')
                  ->orWhere('penyusun', 'like', '%' . $search . '%')
                  ->orWhere('jenis_surat', 'like', '%' . $search . '%');
            });
        }

        // PENGECEKAN ROLE:
        // Role 0 = Admin, Role 2 = Ajudan (Dibatasi 10 data terbaru berdasarkan tanggal acara)
        // Role 1 = Super Admin (Melihat seluruh data)
        $userRole = auth()->user()->role;

        if (in_array($userRole, [0, 2])) {
            // Admin (0) & Ajudan (2): Ambil 10 data dengan tanggal & waktu terbaru
            $agenda = $query->orderBy('tanggal', 'desc')
                            ->orderBy('waktu', 'desc')
                            ->take(10)
                            ->get();
        } else {
            // Super Admin (1) dan role lainnya: Ambil semua data diurutkan dari tanggal & waktu terbaru
            $agenda = $query->orderBy('tanggal', 'desc')
                            ->orderBy('waktu', 'desc')
                            ->get();
        }

        return view('backend.v_sambutan.index', compact('agenda'));
    }

    public function create()
    {
        $judul = "Tambah Data Surat";
        return view('backend.v_sambutan.create', compact('judul'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'acara'         => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'waktu'         => 'required|string',
            'tempat'        => 'required|string|max:255',
            'penyusun'      => 'required|string',
            'link'          => 'nullable|url',
            'kategori'      => 'required|in:Sambutan,Keprotokolan,Sambutan dan Keprotokolan',
            'status'        => 'required|in:Publik,Privat',
            'file_surat'    => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:10240',
        ]);

        // 2. Upload File
        $fileName = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->storeAs('agenda', $fileName, 'public');
        }

        // 3. Simpan SEMUA opsi kategori ke dalam 1 TABEL (tabel sambutan)
        Sambutan::create([
            'acara'         => $request->acara,
            'penyelenggara' => $request->penyelenggara,
            'tanggal'       => $request->tanggal,
            'waktu'         => $request->waktu,
            'tempat'        => $request->tempat,
            'penyusun'      => $request->penyusun,
            'link'          => $request->link,
            'jenis_surat'   => $request->kategori, // Menyimpan opsi 'Keprotokolan' atau 'Sambutan'
            'status'        => $request->status,
            'gambar'        => $fileName,
        ]);

        return redirect()->route('backend.sambutan.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $judul = "Edit Data Surat";
        $row = Sambutan::findOrFail($id); 
        
        return view('backend.v_sambutan.edit', compact('judul', 'row'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'acara'         => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'jenis_surat'   => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'waktu'         => 'required',
            'tempat'        => 'required|string|max:255',
            'penyusun'      => 'required|string|max:255',
            'status'        => 'required|in:Publik,Privat',
            'link'          => 'nullable|url',
            'gambar'        => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        $sambutan = Sambutan::findOrFail($id);

        if ($request->hasFile('gambar')) {
            if ($sambutan->gambar && file_exists(public_path('storage/agenda/' . $sambutan->gambar))) {
                unlink(public_path('storage/agenda/' . $sambutan->gambar));
            }

            $image = $request->file('gambar');
            $nama_gambar = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/agenda'), $nama_gambar);
            $validatedData['gambar'] = $nama_gambar;
        } else {
            unset($validatedData['gambar']);
        }

        $sambutan->update($validatedData);

        return redirect()->route('backend.sambutan.index')->with('sukses', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $sambutan = Sambutan::findOrFail($id);

        if ($sambutan->gambar && file_exists(public_path('storage/agenda/' . $sambutan->gambar))) {
            unlink(public_path('storage/agenda/' . $sambutan->gambar));
        }

        $sambutan->delete();

        return redirect()->route('backend.sambutan.index')->with('sukses', 'Data berhasil dihapus!');
    }

    // --- METHOD FRONTEND (PUBLIK) ---

    // Halaman Frontend Sambutan
    public function frontendIndex(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $query = Sambutan::where('status', 'Publik')
                         ->whereIn('jenis_surat', ['Sambutan', 'Sambutan dan Keprotokolan'])
                         // Hanya tampil jika waktu acara (Tanggal + Waktu) <= Waktu sekarang
                         ->whereRaw("CONCAT(tanggal, ' ', waktu) <= ?", [$now]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('acara', 'like', '%' . $search . '%');
        }

        $suratSambutan = $query->orderBy('tanggal', 'desc')
                               ->orderBy('waktu', 'desc')
                               ->get();

        return view('frontend.v_sambutan.index', compact('suratSambutan'));
    }

    // Halaman Frontend Keprotokolan (Tetap membaca dari 1 tabel yang sama)
    public function frontendKeprotokolan(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $query = Sambutan::where('status', 'Publik')
                         ->whereIn('jenis_surat', ['Keprotokolan', 'Sambutan dan Keprotokolan'])
                         // Hanya tampil jika waktu acara (Tanggal + Waktu) <= Waktu sekarang
                         ->whereRaw("CONCAT(tanggal, ' ', waktu) <= ?", [$now]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('acara', 'like', '%' . $search . '%');
        }

        $dataKeprotokolan = $query->orderBy('tanggal', 'desc')
                                  ->orderBy('waktu', 'desc')
                                  ->get();

        return view('frontend.v_keprotokolan.index', compact('dataKeprotokolan'));
    }
}