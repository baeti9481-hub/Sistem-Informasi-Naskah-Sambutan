@extends('backend.v_layouts.app')

@section('content')
<!-- Tombol Tambah (Sembunyikan dari Ajudan) -->
@if (Auth::user()->role != '2')
<div class="row mb-3">
  <div class="col-12">
    <a href="{{ route('backend.sambutan.create') }}" class="btn btn-primary font-weight-medium">
      <i class="mdi mdi-plus"></i> Tambah
    </a>
  </div>
</div>
@endif

<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        
        <h4 class="card-title mb-4">Data Surat</h4>

        <!-- Alert Pesan Sukses -->
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @elseif(session('sukses'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('sukses') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        <!-- Style Khusus Agar Scroll Hanya Terjadi di Tabel -->
        <style>
          .custom-table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
          }

          #dataTableSambutan th, 
          #dataTableSambutan td {
            white-space: nowrap !important;
            vertical-align: middle;
          }

          .action-buttons {
            white-space: nowrap !important;
          }
        </style>

        <!-- Tabel Data Sambutan -->
        <table id="dataTableSambutan" class="table table-bordered table-hover w-100">
          <thead>
            <tr>
              <th width="5%">No</th>
              
              {{-- JIKA USER ADALAH AJUDAN (ROLE 2) --}}
              @if (Auth::user()->role == '2')
                <th>Acara</th>
                <th>Link Sambutan</th>
                <th>Tempat</th>
                <th>Penyusun</th>
                <th>Penyelenggara</th>
                <th>Jenis Surat</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Dokumen</th>
              @else
                {{-- UNTUK ROLE LAIN (SUPER ADMIN & ADMIN) --}}
                <th>Acara</th>
                <th>Penyelenggara</th>
                <th>Jenis Surat</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Tempat</th>
                <th>Penyusun</th>
                <th>Link</th>
                <th>Dokumen</th>
                <th width="1%">Aksi</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @foreach ($agenda as $row)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>

              {{-- JIKA USER ADALAH AJUDAN (ROLE 2) --}}
              @if (Auth::user()->role == '2')
                <td>{{ $row->acara }}</td>
                
                {{-- KOLOM LINK --}}
                <td class="text-center">
                  @if($row->link)
                    <a href="{{ $row->link }}" target="_blank" class="btn btn-sm btn-info text-white">
                      <i class="fas fa-external-link-alt"></i> Buka Link
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>

                <td>{{ $row->tempat }}</td>
                <td>{{ $row->penyusun ?? '-' }}</td>
                <td>{{ $row->penyelenggara }}</td>
                <td>{{ $row->jenis_surat ?? '-' }}</td> 
                <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $row->waktu }}</td>

                {{-- KOLOM DOKUMEN --}}
                <td class="text-center">
                  @if($row->gambar)
                    <a href="{{ asset('storage/agenda/' . $row->gambar) }}" target="_blank" class="btn btn-sm btn-secondary text-white">
                      <i class="fas fa-file-alt"></i> File
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>

              @else
                {{-- UNTUK ROLE LAIN (SUPER ADMIN & ADMIN) --}}
                <td>{{ $row->acara }}</td>
                <td>{{ $row->penyelenggara }}</td>
                <td>{{ $row->jenis_surat ?? '-' }}</td> 
                <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $row->waktu }}</td>
                <td>{{ $row->tempat }}</td>
                <td>{{ $row->penyusun ?? '-' }}</td>
                
                {{-- KOLOM LINK --}}
                <td class="text-center">
                  @if($row->link)
                    <a href="{{ $row->link }}" target="_blank" class="btn btn-sm btn-info text-white">
                      <i class="fas fa-external-link-alt"></i> Buka Link
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>

                {{-- KOLOM DOKUMEN --}}
                <td class="text-center">
                  @if($row->gambar)
                    <a href="{{ asset('storage/agenda/' . $row->gambar) }}" target="_blank" class="btn btn-sm btn-secondary text-white">
                      <i class="fas fa-file-alt"></i> File
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>

                <!-- Tombol Ubah & Hapus -->
                <td class="action-buttons">
                  <!-- Form Ubah -->
                  <a href="{{ route('backend.sambutan.edit', $row->id) }}" class="btn btn-info btn-sm text-white" title="Ubah Data">
                    <i class="far fa-edit"></i> Ubah
                  </a>

                  <!-- Form Hapus -->
                  <form method="POST" action="{{ route('backend.sambutan.destroy', $row->id) }}" style="display: inline-block;">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->acara }}" title="Hapus Data">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>

<!-- Script Inisialisasi DataTables -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
      var table = $('#dataTableSambutan').DataTable({
        "scrollX": false,
        "autoWidth": false,
        "language": {
          "lengthMenu": "Show _MENU_ entries",
          "zeroRecords": "Data tidak ditemukan",
          "info": "Showing _START_ to _END_ of _TOTAL_ entries",
          "infoEmpty": "Showing 0 to 0 of 0 entries",
          "infoFiltered": "(difilter dari _MAX_ total data)",
          "search": "Search:",
          "paginate": {
            "first": "First",
            "last": "Last",
            "next": "Next",
            "previous": "Previous"
          }
        },
        "initComplete": function() {
          $('#dataTableSambutan').wrap('<div class="custom-table-wrapper"></div>');
        }
      });
    }
  });
</script>
@endsection