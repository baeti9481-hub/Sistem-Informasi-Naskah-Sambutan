@extends('backend.v_layouts.app')

@section('content')
<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="w-100">

      <!-- Tombol Tambah Data (Sembunyikan untuk Role 2 / Ajudan) -->
      @if(Auth::user()->role != 2)
      <div class="row mb-3">
        <div class="col-12">
          <a href="{{ route('backend.keprotokolan.create') }}" class="btn btn-primary font-weight-medium">
            <i class="mdi mdi-plus"></i> Tambah
          </a>
        </div>
      </div>
      @endif

      <!-- Card Utama Tempat Tabel -->
      <div class="card">
        <div class="card-body">
          
          <!-- Alert Pesan Sukses -->
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
              {{ session('success') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @elseif(session('sukses'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
              {{ session('sukses') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif

          <!-- Judul Tabel di Dalam Card -->
          <h5 class="card-title font-weight-bold text-uppercase mb-4">DATA KEPROTOKOLAN</h5>

          <style>
            .custom-table-wrapper {
              width: 100%;
              overflow-x: auto;
              -webkit-overflow-scrolling: touch;
              margin-bottom: 1rem;
            }

            #dataTable th, 
            #dataTable td {
              white-space: nowrap !important;
              vertical-align: middle;
            }

            .action-buttons {
              white-space: nowrap !important;
            }
          </style>

          <!-- Tabel Data Keprotokolan -->
          <table id="dataTable" class="table table-bordered table-hover w-100">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th>Nama Acara / Kegiatan</th>
                <th>Tanggal</th>
                <th class="text-center">Dokumen Surat / File Undangan</th>
                <!-- Tampilkan Header Aksi Hanya Jika Bukan Role 2 -->
                @if(Auth::user()->role != 2)
                <th width="15%" class="text-center">Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($pengajuan as $key => $item)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                
                <td><strong>{{ $item->nama_acara }}</strong></td>
                <td>{{ date('d-m-Y', strtotime($item->tanggal_pelaksanaan)) }}</td>

                <td class="text-center">
                  @if($item->file_undangan)
                    <!-- Path disesuaikan ke storage/agenda/ -->
                    <a href="{{ asset('storage/agenda/' . $item->file_undangan) }}" target="_blank" class="btn btn-sm btn-info text-white">
                      <i class="fas fa-file-pdf"></i> Unduh
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>

                <!-- Tampilkan Kolom Aksi Hanya Jika Bukan Role 2 -->
                @if(Auth::user()->role != 2)
                <td class="action-buttons text-center">
                  <a href="{{ route('backend.keprotokolan.edit', $item->id) }}" class="btn btn-info btn-sm text-white" title="Edit Data">
                    <i class="fas fa-edit"></i> Ubah
                  </a>

                  <form method="POST" action="{{ route('backend.keprotokolan.destroy', $item->id) }}" style="display: inline-block;">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $item->nama_acara }}" title="Hapus Data">
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
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
      var table = $('#dataTable').DataTable({
        "scrollX": false,
        "autoWidth": false,
        "language": {
          "lengthMenu": "Show _MENU_ entries",
          "zeroRecords": "Data pengajuan tidak ditemukan",
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
          $('#dataTable').wrap('<div class="custom-table-wrapper"></div>');
        }
      });
    }
  });
</script>
@endsection