@extends('backend.v_layouts.app')

@section('content')
{{-- Di HP padding container dibuat lebih tipis (px-2), di PC tetap px-md-4 --}}
<div class="container-fluid pt-3 pt-md-4 px-2 px-md-4">
    <div class="row g-0">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                {{-- Form action diarahkan ke rute store sambutan --}}
                <form class="form-horizontal w-100" action="{{ route('backend.sambutan.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                 
                    <div class="card-body p-3 p-md-4">
                        
                        <div class="mb-3 mb-md-4">
                            <h5 class="card-title fw-bold mb-1">{{ $judul ?? 'Tambah Surat Sambutan' }}</h5>
                            <p class="text-muted small mb-0">Silakan isi formulir di bawah ini untuk menambahkan data Surat.</p>
                        </div>
                        
                        <div class="row text-start text-dark g-3">
                            
                            {{-- Sisi Kiri: Preview & Input Unggah Berkas/Scan Surat --}}
                            <div class="col-12 col-md-4">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Dokumen / Scan Surat <span class="text-danger">*</span></label>
                                    <div class="mb-3 mt-1 text-center">
                                        {{-- 1. Wadah Preview Gambar --}}
                                        <img class="foto-preview img-fluid img-thumbnail rounded-3 w-100" style="max-height: 250px; display: none; object-fit: cover; margin: 0 auto;">
                                        
                                        {{-- 2. Wadah Preview Dokumen PDF / Word --}}
                                        <div class="doc-preview border rounded p-3 p-md-4 bg-light text-center" style="display: none;">
                                            <i class="doc-icon fa fa-file-alt fa-3x fa-md-4x mb-2"></i>
                                            <div class="doc-name fw-bold text-truncate text-dark small"></div>
                                            <small class="doc-size text-muted d-block mt-1"></small>
                                        </div>

                                        {{-- 3. Wadah Placeholder Bawaan --}}
                                        <div class="placeholder-upload border rounded p-4 p-md-5 text-muted bg-light">
                                            <i class="fa fa-file-upload fa-2x fa-md-3x d-block mb-2"></i>
                                            <span class="small">Belum ada berkas terpilih</span>
                                        </div>
                                    </div>

                                    {{-- Input File --}}
                                    <input type="file" name="file_surat" id="file_surat" class="form-control form-control-sm form-control-md @error('file_surat') is-invalid @enderror" onchange="previewBerkas()" accept=".pdf,.doc,.docx,image/*" required>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Format: PDF, DOC, DOCX, JPG, PNG (Maks. 5MB)</small>
                                    
                                    @error('file_surat')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Sisi Kanan: Detail Isian Sambutan --}}
                            <div class="col-12 col-md-8">
                                {{-- NAMA ACARA --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Nama Acara / Kegiatan <span class="text-danger">*</span></label>
                                    <input type="text" name="acara" value="{{ old('acara') }}" class="form-control @error('acara') is-invalid @enderror" placeholder="Masukkan nama acara" required>
                                    @error('acara')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PENYELENGGARA --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Penyelenggara <span class="text-danger">*</span></label>
                                    <input type="text" name="penyelenggara" value="{{ old('penyelenggara') }}" class="form-control @error('penyelenggara') is-invalid @enderror" placeholder="Masukkan nama instansi" required>
                                    @error('penyelenggara')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row g-2 mb-3">
                                    {{-- TANGGAL --}}
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label class="fw-semibold small text-secondary mb-1">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                            <input type="date" name="tanggal" value="{{ old('tanggal') }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                                            @error('tanggal')
                                                <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- WAKTU --}}
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label class="fw-semibold small text-secondary mb-1">Waktu Kegiatan <span class="text-danger">*</span></label>
                                            <input type="text" name="waktu" value="{{ old('waktu') }}" class="form-control @error('waktu') is-invalid @enderror" placeholder="Contoh: 19.30 WIB" required>
                                            @error('waktu')
                                                <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- TEMPAT --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Tempat / Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" name="tempat" value="{{ old('tempat') }}" class="form-control @error('tempat') is-invalid @enderror" placeholder="Masukkan lokasi acara" required>
                                    @error('tempat')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PENYUSUN --}}
                                @if(auth()->user()->role == '1')
                                    <div class="form-group mb-3">
                                        <label class="fw-semibold small text-secondary mb-1">Penyusun <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            name="penyusun" 
                                            list="penyusun-list" 
                                            class="form-control @error('penyusun') is-invalid @enderror" 
                                            placeholder="Pilih dari daftar atau ketik nama penyusun"
                                            value="{{ old('penyusun') }}"
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
                                    </div>
                                @else
                                    <input type="hidden" name="penyusun" value="{{ auth()->user()->nama ?? auth()->user()->name }}">
                                @endif

                                {{-- LINK SURAT / DOKUMEN --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Link Sambutan (Word / Cloud Storage) <span class="text-muted fw-normal">(Opsional)</span></label>
                                    <input type="url" name="link" value="{{ old('link') }}" class="form-control @error('link') is-invalid @enderror" placeholder="Contoh: https://drive.google.com/file/...">
                                    @error('link')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- KATEGORI --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Jenis Surat <span class="text-danger">*</span></label>
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                        <option value="" selected disabled>-- Jenis Surat --</option>
                                        <option value="Sambutan" {{ old('kategori') == 'Sambutan' ? 'selected' : '' }}>Sambutan</option>
                                        <option value="Keprotokolan" {{ old('kategori') == 'Keprotokolan' ? 'selected' : '' }}>Keprotokolan</option>
                                        <option value="Sambutan dan Keprotokolan" {{ old('kategori') == 'Sambutan dan Keprotokolan' ? 'selected' : '' }}>Sambutan dan Keprotokolan</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- STATUS PUBLIKASI --}}
                                <div class="form-group mb-3">
                                    <label class="fw-semibold small text-secondary mb-1">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="Publik" {{ old('status', 'Publik') == 'Publik' ? 'selected' : '' }}>Publik</option>
                                        <option value="Privat" {{ old('status') == 'Privat' ? 'selected' : '' }}>Privat</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Bagian Tombol Aksi --}}
                    <div class="border-top bg-light p-3 p-md-4 rounded-bottom-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn text-white px-4" style="background-color: #1a408c; font-weight: 500;">
                                <i class="fas fa-save me-2"></i>Simpan
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
    function previewBerkas() {
        const input = document.getElementById('file_surat');
        const imgPreview = document.querySelector('.foto-preview');
        const docPreview = document.querySelector('.doc-preview');
        const docIcon = document.querySelector('.doc-icon');
        const docName = document.querySelector('.doc-name');
        const docSize = document.querySelector('.doc-size');
        const placeholder = document.querySelector('.placeholder-upload');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileName = file.name;
            const fileExtension = fileName.split('.').pop().toLowerCase();
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

            if (placeholder) placeholder.style.display = 'none';

            if (['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(fileExtension)) {
                if (docPreview) docPreview.style.display = 'none';
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imgPreview.style.display = 'none';
                if (docPreview) {
                    docPreview.style.display = 'block';
                    docName.textContent = fileName;
                    docSize.textContent = fileSizeMB + ' MB';

                    if (fileExtension === 'pdf') {
                        docIcon.className = 'doc-icon fa fa-file-pdf fa-3x fa-md-4x text-danger mb-2';
                    } else if (['doc', 'docx'].includes(fileExtension)) {
                        docIcon.className = 'doc-icon fa fa-file-word fa-3x fa-md-4x text-primary mb-2';
                    } else {
                        docIcon.className = 'doc-icon fa fa-file-alt fa-3x fa-md-4x text-secondary mb-2';
                    }
                }
            }
        } else {
            imgPreview.style.display = 'none';
            if (docPreview) docPreview.style.display = 'none';
            if (placeholder) placeholder.style.display = 'block';
        }
    }
</script>
@endsection