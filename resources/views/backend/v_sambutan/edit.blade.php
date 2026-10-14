@extends('backend.v_layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                {{-- Form action diarahkan ke route update sambutan dengan ID --}}
                <form class="form-horizontal" action="{{ route('backend.sambutan.update', $row->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="fw-bold" style="color: #1a408c;">{{ $judul ?? 'Edit Surat Sambutan' }}</h5>
                            <p class="text-muted small">Silakan perbarui informasi data sambutan di bawah ini.</p>
                        </div>

                        {{-- Menampilkan Ringkasan Pesan Error Validasi (jika ada) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Gagal Menyimpan Data:</strong>
                                <ul class="mb-0 mt-1 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row text-start text-dark">
                            
                            {{-- Sisi Kiri: Preview & Upload Gambar/Scan Surat --}}
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Gambar / Scan Surat Saat Ini</label>
                                    <div class="mb-3 mt-2 text-center">
                                        {{-- Preview Gambar Existing / Baru --}}
                                        @if($row->gambar)
                                            <img id="fotoPreview" src="{{ asset('storage/agenda/' . $row->gambar) }}" class="foto-preview img-fluid img-thumbnail rounded-3" style="max-height: 250px; display: block; object-fit: cover; margin: 0 auto;">
                                            <div id="placeholderUpload" class="placeholder-upload border rounded p-5 text-muted bg-light" style="display: none;">
                                                <i class="fa fa-image fa-3x d-block mb-2"></i>
                                                Belum ada gambar
                                            </div>
                                        @else
                                            <img id="fotoPreview" class="foto-preview img-fluid img-thumbnail rounded-3" style="max-height: 250px; display: none; object-fit: cover; margin: 0 auto;">
                                            <div id="placeholderUpload" class="placeholder-upload border rounded p-5 text-muted bg-light">
                                                <i class="fa fa-image fa-3x d-block mb-2"></i>
                                                Belum ada gambar
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" onchange="previewGambar(this)" accept="image/*">
                                    <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengubah gambar. Format: JPG, JPEG, PNG (Maks. 2MB)</small>
                                    @error('gambar')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Sisi Kanan: Detail Isian Sambutan --}}
                            <div class="col-md-8">
                                
                                {{-- NAMA ACARA --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Nama Acara / Kegiatan <span class="text-danger">*</span></label>
                                    <input type="text" name="acara" value="{{ old('acara', $row->acara) }}" class="form-control @error('acara') is-invalid @enderror" placeholder="Masukkan nama acara resmi..." required>
                                    @error('acara')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PENYELENGGARA --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Penyelenggara <span class="text-danger">*</span></label>
                                    <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $row->penyelenggara) }}" class="form-control @error('penyelenggara') is-invalid @enderror" placeholder="Masukkan nama instansi / penyelenggara..." required>
                                    @error('penyelenggara')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    {{-- TANGGAL --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-semibold small text-secondary mb-1">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                            <input type="date" name="tanggal" value="{{ old('tanggal', $row->tanggal) }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                                            @error('tanggal')
                                                <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- WAKTU --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-semibold small text-secondary mb-1">Waktu Kegiatan <span class="text-danger">*</span></label>
                                            <input type="text" name="waktu" value="{{ old('waktu', $row->waktu) }}" class="form-control @error('waktu') is-invalid @enderror" placeholder="Contoh: 19.30 WIB" required>
                                            @error('waktu')
                                                <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- TEMPAT --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Tempat / Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" name="tempat" value="{{ old('tempat', $row->tempat) }}" class="form-control @error('tempat') is-invalid @enderror" placeholder="Masukkan lokasi acara..." required>
                                    @error('tempat')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PENYUSUN (Input Text + Datalist sama seperti Tambah Data) --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Penyusun <span class="text-danger">*</span></label>
                                    
                                    @if(auth()->user()->role == '1')
                                        {{-- JIKA SUPER ADMIN: Input Text dengan rekomendasi Datalist --}}
                                        <input 
                                            type="text" 
                                            name="penyusun" 
                                            list="penyusun-list" 
                                            class="form-control @error('penyusun') is-invalid @enderror" 
                                            placeholder="Pilih dari daftar atau ketik nama penyusun"
                                            value="{{ old('penyusun', $row->penyusun) }}"
                                            onclick="this.showPicker && this.showPicker()"
                                            autocomplete="off"
                                            required
                                        >

                                        <datalist id="penyusun-list">
                                            <option value="Mokh Atik">
                                            <option value="Azhar">
                                            <option value="Zaza">
                                        </datalist>
                                        @error('penyusun')
                                            <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                        @enderror
                                    @else
                                        {{-- JIKA ADMIN BIASA: Readonly --}}
                                        <input type="text" class="form-control bg-light" value="{{ $row->penyusun }}" readonly>
                                        <input type="hidden" name="penyusun" value="{{ old('penyusun', $row->penyusun) }}">
                                        <small class="text-muted d-block mt-1">Nama penyusun telah ditentukan oleh Super Admin dan tidak dapat diubah.</small>
                                    @endif
                                </div>

                                {{-- LINK SURAT / DOKUMEN --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Link Sambutan (Word / Cloud Storage)</label>
                                    <input type="url" name="link" value="{{ old('link', $row->link) }}" class="form-control @error('link') is-invalid @enderror" placeholder="Contoh: https://drive.google.com/file/...">
                                    @error('link')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- JENIS SURAT (DI ATAS STATUS) --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Jenis Surat <span class="text-danger">*</span></label>
                                    <select name="jenis_surat" class="form-select @error('jenis_surat') is-invalid @enderror" required>
                                        <option value="" disabled>-- Pilih Jenis Surat --</option>
                                        <option value="Sambutan" {{ old('jenis_surat', $row->jenis_surat ?? '') == 'Sambutan' ? 'selected' : '' }}>Sambutan</option>
                                        <option value="Undangan" {{ old('jenis_surat', $row->jenis_surat ?? '') == 'Undangan' ? 'selected' : '' }}>Undangan</option>
                                        <option value="Keterangan" {{ old('jenis_surat', $row->jenis_surat ?? '') == 'Keterangan' ? 'selected' : '' }}>Keterangan</option>
                                        <option value="Permohonan" {{ old('jenis_surat', $row->jenis_surat ?? '') == 'Permohonan' ? 'selected' : '' }}>Permohonan</option>
                                        <option value="Lainnya" {{ old('jenis_surat', $row->jenis_surat ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('jenis_surat')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- STATUS PUBLIKASI --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="Publik" {{ old('status', $row->status) == 'Publik' ? 'selected' : '' }}>Publik</option>
                                        <option value="Privat" {{ old('status', $row->status) == 'Privat' ? 'selected' : '' }}>Privat</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Bagian Tombol Aksi --}}
                    <div class="border-top bg-light p-4 rounded-bottom-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn text-white px-4" style="background-color: #1a408c; font-weight: 500;">
                                <i class="fas fa-save me-2"></i>Perbarui Data
                            </button>
                            <a href="{{ route('backend.sambutan.index') }}" class="btn btn-light border px-4 text-secondary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi Javascript untuk Preview Gambar secara Realtime
    function previewGambar(input) {
        const previewImg = document.getElementById('fotoPreview');
        const placeholder = document.getElementById('placeholderUpload');
        
        const file = input.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                previewImg.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
            }
        }
    }
</script>
@endsection