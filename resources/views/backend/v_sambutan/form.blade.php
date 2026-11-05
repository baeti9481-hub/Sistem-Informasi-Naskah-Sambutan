@extends('backend.v_layouts.app')

@section('content')
<!-- template -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <form class="form-horizontal" action="{{ route('backend.sambutan.cetak') }}" method="post" target="_blank">
                @csrf

                <div class="card-body">
                    <h4 class="card-title mb-4"> {{$judul}} </h4>

                    <!-- PILIHAN JENIS SURAT -->
                    <div class="form-group mb-3">
                        <label class="fw-semibold small text-secondary mb-1">Jenis Surat</label>
                        <select name="jenis_surat" class="form-control">
                            <option value="">-- Semua Jenis Surat --</option>
                            <option value="Sambutan" {{ old('jenis_surat') == 'Sambutan' ? 'selected' : '' }}>Sambutan</option>
                            <option value="Keprotokolan" {{ old('jenis_surat') == 'Keprotokolan' ? 'selected' : '' }}>Keprotokolan</option>
                            <option value="Sambutan & Keprotokolan" {{ old('jenis_surat') == 'Sambutan & Keprotokolan' ? 'selected' : '' }}>Sambutan & Keprotokolan</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-semibold small text-secondary mb-1">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" value="{{ old('tanggal_awal') }}" class="form-control @error('tanggal_awal') is-invalid @enderror">
                        @error('tanggal_awal')
                        <span class="invalid-feedback alert-danger p-1 rounded" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-semibold small text-secondary mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}" class="form-control @error('tanggal_akhir') is-invalid @enderror">
                        @error('tanggal_akhir')
                        <span class="invalid-feedback alert-danger p-1 rounded" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <br>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-print me-2"></i> Cetak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- end template-->
@endsection