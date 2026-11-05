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

        // Semua role dapat melihat seluruh data secara keseluruhan
        $agenda = $query->orderBy('tanggal', 'desc')
                        ->orderBy('waktu', 'desc')
                        ->get();

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
            'kategori'      => 'required|in:Sambutan,Keprotokolan,Sambutan & Keprotokolan',
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

        // 3. Simpan ke database
        Sambutan::create([
            'acara'         => $request->acara,
            'penyelenggara' => $request->penyelenggara,
            'tanggal'       => $request->tanggal,
            'waktu'         => $request->waktu,
            'tempat'        => $request->tempat,
            'penyusun'      => $request->penyusun,
            'link'          => $request->link,
            'jenis_surat'   => $request->kategori,
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

        // Menampilkan jenis Sambutan DAN Sambutan & Keprotokolan
        $query = Sambutan::where('status', 'Publik')
                         ->whereIn('jenis_surat', ['Sambutan', 'Sambutan & Keprotokolan'])
                         ->whereRaw("CONCAT(tanggal, ' ', waktu) <= ?", [$now]);

        // FITUR PENCARIAN (Acara, Penyelenggara, Tempat, Tanggal)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('acara', 'like', '%' . $search . '%')
                  ->orWhere('penyelenggara', 'like', '%' . $search . '%')
                  ->orWhere('tempat', 'like', '%' . $search . '%')
                  ->orWhere('tanggal', 'like', '%' . $search . '%')
                  ->orWhereRaw("DATE_FORMAT(tanggal, '%d-%m-%Y') LIKE ?", ['%' . $search . '%']);
            });
        }

        // Ambil jumlah entries dari request (default 10) dan gunakan paginate
        $perPage = $request->input('per_page', 10);
        $suratSambutan = $query->orderBy('tanggal', 'desc')
                               ->orderBy('waktu', 'desc')
                               ->paginate($perPage)
                               ->withQueryString();

        return view('frontend.v_sambutan.index', compact('suratSambutan'));
    }

    // Halaman Frontend Keprotokolan
    public function frontendKeprotokolan(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        // Menampilkan jenis Keprotokolan DAN Sambutan & Keprotokolan
        $query = Sambutan::where('status', 'Publik')
                         ->whereIn('jenis_surat', ['Keprotokolan', 'Sambutan & Keprotokolan'])
                         ->whereRaw("CONCAT(tanggal, ' ', waktu) <= ?", [$now]);

        // FITUR PENCARIAN (Acara, Penyelenggara, Tempat, Tanggal)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('acara', 'like', '%' . $search . '%')
                  ->orWhere('penyelenggara', 'like', '%' . $search . '%')
                  ->orWhere('tempat', 'like', '%' . $search . '%')
                  ->orWhere('tanggal', 'like', '%' . $search . '%')
                  ->orWhereRaw("DATE_FORMAT(tanggal, '%d-%m-%Y') LIKE ?", ['%' . $search . '%']);
            });
        }

        // Ambil jumlah entries dari request (default 10) dan gunakan paginate
        $perPage = $request->input('per_page', 10);
        $dataKeprotokolan = $query->orderBy('tanggal', 'desc')
                                  ->orderBy('waktu', 'desc')
                                  ->paginate($perPage)
                                  ->withQueryString();

        return view('frontend.v_keprotokolan.index', compact('dataKeprotokolan'));
    }


    // --- METHOD LAPORAN & CETAK SURAT ---

    public function formSurat()
    {
        $judul = "Laporan Data Surat";
        return view('backend.v_sambutan.form', compact('judul'));
    }

    public function cetakSurat(Request $request)
    {
        // Menambahkan aturan validasi
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ], [
            'tanggal_awal.required' => 'Tanggal Awal harus diisi.',
            'tanggal_akhir.required' => 'Tanggal Akhir harus diisi.',
            'tanggal_akhir.after_or_equal' => 'Tanggal Akhir harus lebih besar atau sama dengan Tanggal Awal.',
        ]);

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $jenisSurat = $request->input('jenis_surat');

        // Query dasar berdasarkan rentang tanggal
        $query = Sambutan::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        // Jika jenis surat dipilih, filter berdasarkan jenis surat tersebut
        if (!empty($jenisSurat)) {
            $query->where('jenis_surat', $jenisSurat);
        }

        $surat = $query->orderBy('tanggal', 'desc')
                       ->orderBy('waktu', 'desc')
                       ->get();

        return view('backend.v_sambutan.cetak', [
            'judul' => 'Laporan Data Surat' . ($jenisSurat ? ' - ' . $jenisSurat : ''),
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'jenisSurat' => $jenisSurat,
            'cetak' => $surat
        ]);
    }
}