@extends('backend.v_layouts.app')

@section('content')
<!-- Tombol Tambah (Sembunyikan dari Ajudan) -->
@if (Auth::user()->role != '2')
<div class="row mb-3">
  <div class="col-12">
    <a href="{{ route('backend.user.create') }}" class="btn btn-primary font-weight-medium">
      <i class="mdi mdi-plus"></i> Tambah
    </a>
  </div>
</div>
@endif

<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        
        <h4 class="card-title mb-4">{{ $judul ?? 'Data User' }}</h4>

        <!-- Alert Pesan Sukses -->
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        <!-- Tabel Data User -->
        <div class="table-responsive">
          <table id="dataTableUser" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Email</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Status</th>
                <!-- Tampilkan Header Aksi hanya jika BUKAN Ajudan -->
                @if (Auth::user()->role != '2')
                  <th width="18%">Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach ($index as $row)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->name ?? $row->nama }}</td>
                <td>
                  @if ($row->role == 1)
                    <span class="badge badge-success">Super Admin</span>
                  @elseif ($row->role == 2)
                    <span class="badge badge-info">Ajudan</span>
                  @else
                    <span class="badge badge-primary">Admin</span>
                  @endif
                </td>
                <td>
                  @if ($row->status == 1)
                    <span class="badge badge-success">Aktif</span>
                  @else
                    <span class="badge badge-secondary">NonAktif</span>
                  @endif
                </td>
                
                <!-- Tampilkan Tombol Ubah & Hapus hanya jika BUKAN Ajudan -->
                @if (Auth::user()->role != '2')
                <td>
                  <!-- Form Ubah -->
                  <a href="{{ route('backend.user.edit', $row->id) }}" class="btn btn-info btn-sm text-white" title="Ubah Data">
                    <i class="far fa-edit"></i> Ubah
                  </a>

                  <!-- Form Hapus -->
                  <form method="POST" action="{{ route('backend.user.destroy', $row->id) }}" style="display: inline-block;">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nama }}" title="Hapus Data">
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

<!-- Script Inisialisasi DataTables -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
      $('#dataTableUser').DataTable({
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
        }
      });
    }
  });
</script>
@endsection