@extends('Backend.V_Layouts.App')
@section('content')
<!-- contentAwal -->

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form class="form-horizontal" action="{{ route('backend.keprotokolan.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <h4 class="card-title"> {{ $judul ?? 'Tambah Keprotokolan' }} </h4>
                        <div class="row">
                         <!-- Kolom Kiri: Upload File Undangan (Gambar, PDF, Word) -->
<div class="col-md-4">
    <div class="form-group mb-3">
        <label class="fw-semibold small text-secondary mb-1">File Dokumen <span class="text-danger">*</span></label>
        <div class="mb-3 mt-2 text-center">
            {{-- Wadah Preview Gambar --}}
            <img class="foto-preview img-fluid img-thumbnail rounded-3" style="max-height: 250px; display: none; object-fit: cover; margin: 0 auto;">
            
            {{-- Wadah Preview Dokumen (PDF / Word) --}}
            <div class="doc-preview border rounded p-4 bg-light text-primary" style="display: none;">
                <i class="doc-icon fa fa-file-text fa-3x d-block mb-2"></i>
                <span class="doc-name small text-break fw-bold"></span>
            </div>

            {{-- Placeholder Default Saat Belum Ada File --}}
            <div class="placeholder-upload border rounded p-5 text-muted bg-light">
                <i class="fa fa-upload fa-3x d-block mb-2"></i>
                Belum ada file terpilih
            </div>
        </div>

        <input type="file" name="file_undangan" class="form-control @error('file_undangan') is-invalid @enderror" onchange="previewFile(this)" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
        <small class="text-muted d-block mt-1">Format: PDF, Word, atau Gambar (Maks. 2MB)</small>

        @error('file_undangan')
            <div class="invalid-feedback d-block mt-1 alert-danger p-1 rounded small text-white">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Script JavaScript untuk Preview Dinamis --}}
<script>
function previewFile(input) {
    const imgPreview = document.querySelector('.foto-preview');
    const docPreview = document.querySelector('.doc-preview');
    const docIcon = document.querySelector('.doc-icon');
    const docName = document.querySelector('.doc-name');
    const placeholder = document.querySelector('.placeholder-upload');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileName = file.name;
        const fileExt = fileName.split('.').pop().toLowerCase();

        // Sembunyikan placeholder awal
        placeholder.style.display = 'none';

        if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
            // Jika file Gambar
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block';
                docPreview.style.display = 'none';
            }
            reader.readAsDataURL(file);
        } else {
            // Jika file PDF atau Word
            imgPreview.style.display = 'none';
            docPreview.style.display = 'block';
            docName.textContent = fileName;

            if (fileExt === 'pdf') {
                docIcon.className = 'doc-icon fa fa-file-pdf-o fa-3x d-block mb-2 text-danger';
            } else if (['doc', 'docx'].includes(fileExt)) {
                docIcon.className = 'doc-icon fa fa-file-word-o fa-3x d-block mb-2 text-primary';
            } else {
                docIcon.className = 'doc-icon fa fa-file-text fa-3x d-block mb-2 text-secondary';
            }
        }
    } else {
        // Reset jika batal memilih file
        imgPreview.style.display = 'none';
        docPreview.style.display = 'none';
        placeholder.style.display = 'block';
    }
}
</script>

                            <!-- Kolom Kanan: Detail Acara -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Nama Acara / Kegiatan</label>
                                    <input type="text" name="nama_acara" value="{{ old('nama_acara') }}" class="form-control @error('nama_acara') is-invalid @enderror" placeholder="Masukkan Nama Acara">
                                    @error('nama_acara')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Pelaksanaan</label>
                                    <input type="date" name="tanggal_pelaksanaan" value="{{ old('tanggal_pelaksanaan') }}" class="form-control @error('tanggal_pelaksanaan') is-invalid @enderror">
                                    @error('tanggal_pelaksanaan')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('backend.keprotokolan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- contentAkhir -->
@endsection