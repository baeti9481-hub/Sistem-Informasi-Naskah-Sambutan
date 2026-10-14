@extends('frontend.v_layouts.app')

@section('title', 'Layanan Keprotokolan')
@section('page_title', 'Daftar surat Keprotokolan')
@section('page_subtitle', 'Daftar riwayat layanan keprotokolan ')

@section('content')
<!-- STYLE KHUSUS: Mengintegrasikan variabel tema layout & mereset background Bootstrap -->
<style>
    /* Card Utama */
    .card-custom {
        background-color: var(--bg-navbar) !important;
        color: var(--text-main) !important;
        border: 1px solid var(--border-color) !important;
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    /* Reset total background Bootstrap pada tabel agar transparan & mengikuti mode tema */
    .table-custom,
    .table-custom th,
    .table-custom td,
    .table-custom thead,
    .table-custom tbody,
    .table-custom tr {
        background-color: transparent !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }

    /* Header Tabel */
    .table-custom thead tr {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }

    /* Teks Sekunder/Muted */
    .table-custom .text-muted,
    .table-custom small {
        color: var(--text-muted) !important;
    }

    /* Baris Selang-Seling (Zebra) & Hover Adaptif */
    :root[data-theme="dark"] .table-custom tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }
    :root[data-theme="dark"] .table-custom tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.07) !important;
    }

    :root[data-theme="light"] .table-custom tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02) !important;
    }
    :root[data-theme="light"] .table-custom tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.04) !important;
    }

    /* Form Input Search & Select */
    .input-search-custom,
    .select-entries-custom {
        background-color: var(--bg-body) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }
    .input-search-custom::placeholder {
        color: var(--text-muted) !important;
    }

    /* Banner Bantuan Bawah */
    .banner-help-custom {
        background-color: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-main) !important;
    }

    /* Styling Tambahan Paginasi Custom */
    .pagination-custom .page-link {
        background-color: var(--bg-body);
        color: var(--text-main);
        border-color: var(--border-color);
    }
    .pagination-custom .page-item.active .page-link {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #ffffff !important;
    }
    .pagination-custom .page-item.disabled .page-link {
        color: var(--text-muted);
        background-color: var(--bg-body);
        border-color: var(--border-color);
        opacity: 0.6;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card card-custom p-4 shadow-sm">
            
            <!-- Header Title -->
            <div class="mb-4">
                <h4 class="section-title mb-1" style="color: var(--text-main);">Dokumen Keprotokolan</h4>
                <p class="small mb-0" style="color: var(--text-muted);">Daftar keprotokolan yang dapat diakses oleh publik.</p>
            </div>

            <!-- FILTERS: SHOW ENTRIES (KIRI) & FORM SEARCH (KANAN) -->
            <form action="{{ route('frontend.keprotokolan') }}" method="GET" class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-3">
                
                <!-- SHOW ENTRIES -->
                <div class="d-flex align-items-center gap-2 small" style="color: var(--text-main);">
                    <span>Show</span>
                    <select name="per_page" class="form-select form-select-sm select-entries-custom w-auto" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span>entries</span>
                </div>

                <!-- SEARCH INPUT -->
                <div class="d-flex align-items-center gap-1">
                    <input type="text" name="search" class="form-control form-control-sm input-search-custom" placeholder="Cari nama acara..." value="{{ request('search') }}" style="max-width: 220px;">
                    <button type="submit" class="btn btn-outline-secondary btn-sm px-2 py-1"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <!-- TABEL KEPROTOKOLAN ADAPTIF TEMA -->
            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0" style="font-size: 0.875rem;">
                    <thead>
                        <tr class="fw-bold">
                            <th scope="col" class="text-center py-3" style="width: 45px;">No</th>
                            <th scope="col" class="py-3">Nama Acara</th>
                            <th scope="col" class="py-3" style="width: 140px;">Jenis Surat</th>
                            <th scope="col" class="py-3" style="width: 150px;">Tanggal Acara</th>
                            <th scope="col" class="text-center py-3" style="width: 120px;">Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuan as $index => $item)
                            <tr>
                                <td class="text-center align-top pt-3">
                                    {{ method_exists($pengajuan, 'firstItem') ? $pengajuan->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="align-top pt-3">
                                    <div class="fw-medium mb-1" style="line-height: 1.3; color: var(--text-main);">{{ $item->acara ?? $item->nama_acara ?? '-' }}</div>
                                </td>

                                <!-- KOLOM ISIAN JENIS SURAT -->
                                <td class="align-top pt-3">
                                    <span class="badge bg-primary text-wrap" style="font-size: 0.75rem; font-weight: 500;">
                                        {{ $item->jenis_surat ?? 'Keprotokolan' }}
                                    </span>
                                </td>

                                <td class="align-top pt-3 text-nowrap">
                                   {{ \Carbon\Carbon::parse($item->tanggal ?? $item->tanggal_acara)->format('d-m-Y') }}
                                </td>
                                
                                <!-- KOLOM DOKUMEN -->
                                <td class="text-center align-top pt-3">
                                    @if(!empty($item->gambar) || !empty($item->file_dokumen))
                                        @php
                                            $file = $item->gambar ?? $item->file_dokumen;
                                        @endphp
                                        <a href="{{ asset('storage/agenda/' . $file) }}" target="_blank" class="btn btn-sm btn-info text-white px-2 py-1 text-nowrap fw-medium" style="font-size: 0.8rem;">
                                            <i class="bi bi-file-earmark-text me-1"></i> File
                                        </a>
                                    @else
                                        <span class="small" style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4" style="color: var(--text-muted);">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada data pengajuan keprotokolan yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- BAGIAN INFO PAGINASI & BUTTONS -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: var(--border-color) !important;">
                <div class="mb-2 mb-md-0 small" style="color: var(--text-main);">
                    @if(method_exists($pengajuan, 'total'))
                        Showing {{ $pengajuan->firstItem() ?? 0 }} to {{ $pengajuan->lastItem() ?? 0 }} of {{ $pengajuan->total() }} entries
                    @else
                        Showing 1 to {{ count($pengajuan) }} of {{ count($pengajuan) }} entries
                    @endif
                </div>

                <div>
                    @if(method_exists($pengajuan, 'links'))
                        <div class="pagination-custom">
                            {{ $pengajuan->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <!-- Tampilan Navigasi Statis jika tidak menggunakan Paginasi Laravel -->
                        <nav>
                            <ul class="pagination pagination-sm pagination-custom mb-0">
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>

            <!-- BANNER BANTUAN PROTOKOL -->
            <div class="p-3 rounded mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 banner-help-custom">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-headset fs-2 text-primary"></i>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.9rem; color: var(--text-main);">Butuh Bantuan mengenai Layanan Keprotokolan?</h6>
                        <small style="font-size: 0.8rem; color: var(--text-muted);">Jika dokumen atau permohonan yang Anda cari belum muncul, silakan hubungi tim keprotokolan.</small>
                    </div>
                </div>
                <a href="https://wa.me/#" target="_blank" class="btn btn-sm btn-outline-success fw-medium text-nowrap px-3 py-1" style="font-size: 0.825rem;">
                    <i class="bi bi-whatsapp me-1"></i> Hubungi Admin Keprotokolan
                </a>
            </div>

        </div>
    </div>
</div>
@endsection