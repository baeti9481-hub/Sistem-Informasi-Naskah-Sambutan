@extends('frontend.v_layouts.app')

@section('title', 'Layanan Surat Sambutan')
@section('page_title', 'Layanan Surat Sambutan')
@section('page_subtitle', 'Daftar dokumen dan informasi layanan surat sambutan resmi')

@section('content')
<!-- STYLE KHUSUS: Mengintegrasikan variabel tema layout ke tabel tanpa merubah layout lain -->
<style>
    .card-custom {
        background-color: var(--bg-navbar) !important;
        color: var(--text-main) !important;
        border: 1px solid var(--border-color) !important;
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

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

    .table-custom thead tr {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: var(--text-main) !important;
        border-bottom: 1px solid var(--border-color) !important;
    }

    .table-custom th, 
    .table-custom td {
        border-color: var(--border-color) !important;
        color: inherit !important;
    }

    .table-custom tbody tr {
        transition: background-color 0.2s ease;
    }

    /* Efek Zebra / Belang-belang adaptif tema */
    :root[data-theme="dark"] .table-custom tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }
    :root[data-theme="light"] .table-custom tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02) !important;
    }

    /* Efek Hover baris tabel */
    :root[data-theme="dark"] .table-custom tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.07) !important;
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

    /* Teks Muted / Sekunder */
    .table-custom .text-muted,
    .table-custom small {
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
                <h4 class="section-title mb-1" style="color: var(--text-main);">Dokumen Surat Sambutan</h4>
                <p class="small mb-0" style="color: var(--text-muted);">Daftar surat sambutan resmi yang dapat diakses oleh publik.</p>
            </div>

            <!-- FILTERS: SHOW ENTRIES (KIRI) & FORM SEARCH (KANAN) -->
            <form action="{{ route('frontend.sambutan') }}" method="GET" class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-3">
                
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
                    <button type="submit" class="btn btn-outline-custom btn-sm px-2 py-1"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <!-- TABEL DENGAN VARIABEL TEMA DARI LAYOUT -->
            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0" style="font-size: 0.875rem;">
                    <thead>
                        <tr class="fw-bold">
                            <th scope="col" class="text-center py-3" style="width: 45px;">No</th>
                            <th scope="col" class="py-3">Nama Acara</th>
                            <th scope="col" class="py-3" style="width: 140px;">Jenis Surat</th>
                            <th scope="col" class="py-3">Instansi / Penyelenggara</th>
                            <th scope="col" class="py-3" style="width: 120px;">Tanggal Acara</th>
                            <th scope="col" class="py-3">Tempat Pelaksanaan</th>
                            <th scope="col" class="text-center py-3" style="width: 100px;">Dokumen</th>
                            <th scope="col" class="text-center py-3" style="width: 120px;">Link Sambutan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratSambutan as $index => $item)
                            <tr>
                                <td class="text-center align-top pt-3">
                                    {{ method_exists($suratSambutan, 'firstItem') ? $suratSambutan->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="align-top pt-3">
                                    <div class="fw-medium mb-1" style="line-height: 1.3; color: var(--text-main);">{{ $item->acara }}</div>
                                </td>
                                
                                <td class="align-top pt-3">
                                    <span class="badge bg-primary text-wrap" style="font-size: 0.75rem; font-weight: 500;">
                                        {{ $item->jenis_surat ?? 'Sambutan' }}
                                    </span>
                                </td>

                                <td class="align-top pt-3 text-uppercase" style="font-size: 0.825rem; width: 200px;">
                                    {{ $item->penyelenggara }}
                                </td>
                                <td class="align-top pt-3 text-nowrap">
                                   {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                                </td>
                                <td class="align-top pt-3">
                                    {{ $item->tempat }}
                                </td>
                                
                                <td class="text-center align-top pt-3">
                                    @if($item->gambar)
                                        <a href="{{ asset('storage/agenda/' . $item->gambar) }}" target="_blank" class="btn btn-sm btn-info text-white px-2 py-1 text-nowrap fw-medium" style="font-size: 0.8rem;">
                                            <i class="bi bi-file-earmark-text me-1"></i> File
                                        </a>
                                    @else
                                        <span style="color: var(--text-muted);" class="small">-</span>
                                    @endif
                                </td>

                                <td class="text-center align-top pt-3">
                                    @if($item->link)
                                        <a href="{{ $item->link }}" target="_blank" class="btn btn-sm btn-success px-2 py-1 text-nowrap fw-medium" style="font-size: 0.8rem;">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                        </a>
                                    @else
                                        <span style="color: var(--text-muted);" class="small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4" style="color: var(--text-muted);">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada data surat sambutan publik yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- BAGIAN INFO PAGINASI & BUTTONS -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: var(--border-color) !important;">
                <div class="mb-2 mb-md-0 small" style="color: var(--text-main);">
                    @if(method_exists($suratSambutan, 'total'))
                        Showing {{ $suratSambutan->firstItem() ?? 0 }} to {{ $suratSambutan->lastItem() ?? 0 }} of {{ $suratSambutan->total() }} entries
                    @else
                        Showing 1 to {{ count($suratSambutan) }} of {{ count($suratSambutan) }} entries
                    @endif
                </div>

                <div>
                    @if(method_exists($suratSambutan, 'links'))
                        <div class="pagination-custom">
                            {{ $suratSambutan->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <!-- Layout Statis Jika Tidak Menggunakan Paginasi Laravel -->
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

            <!-- BANNER BANTUAN PROTOKOL (AUTOMATIS SESUAI TEMA) -->
            <div class="p-3 rounded mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 banner-help-custom">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-headset fs-2 text-primary"></i>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.9rem; color: var(--text-main);">Butuh Bantuan mengenai Surat Sambutan?</h6>
                        <small style="font-size: 0.8rem; color: var(--text-muted);">Jika surat sambutan yang Anda cari belum tersedia, silakan hubungi tim keprotokolan.</small>
                    </div>
                </div>
                <a href="https://wa.me/#" target="_blank" class="btn btn-sm btn-outline-success fw-medium text-nowrap px-3 py-1" style="font-size: 0.825rem;">
                    <i class="bi bi-whatsapp me-1"></i> Hubungi Admin Protokol
                </a>
            </div>

        </div>
    </div>
</div>
@endsection