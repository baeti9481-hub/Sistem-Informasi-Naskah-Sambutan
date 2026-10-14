<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ $judul ?? 'DATA ACARA' }}</title>

  <!-- Google Fonts: Roboto -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('backend/majestic-master/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/majestic-master/vendors/base/vendor.bundle.base.css') }}">

  <!-- plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('backend/majestic-master/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">

  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('backend/dist/css/style.css') }}">

  <!-- Shortcut Icon (Disesuaikan dengan file logo Anda) -->
  <link rel="shortcut icon" href="{{ asset('backend/images/sinas2.png') }}" />
</head>

<body>
  <div class="container-scroller">
    
    <!-- Navbar -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <!-- PERBAIKAN LOGO: Menggunakan helper asset() -->
          <a class="navbar-brand brand-logo" href="{{ route('backend.v_beranda.index') }}">
            <img src="{{ asset('backend/images/sinas2.png') }}" alt="logo"/>
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{ route('backend.v_beranda.index') }}">
            <img src="{{ asset('backend/images/sinas2.png') }}" alt="logo"/>
          </a>

          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-sort-variant"></span>
          </button>
        </div>  
      </div>

      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-4 w-100">
          <li class="nav-item nav-search d-none d-lg-block w-100">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="search">
                  <i class="mdi mdi-magnify"></i>
                </span>
              </div>
              <input type="text" class="form-control" placeholder="Cari" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>

        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown" id="profileDropdown">
              @if(Auth::user()->foto && file_exists(public_path('storage/img-user/' . Auth::user()->foto)))
                <img src="{{ asset('storage/img-user/' . Auth::user()->foto) }}" alt="profile" class="rounded-circle mr-2" style="width: 32px; height: 32px; object-fit: cover;"/>
              @else
                @php
                  $userName = Auth::user()->nama ?? Auth::user()->name ?? 'A';
                  $initial = strtoupper(substr(trim($userName), 0, 1));
                @endphp

                <div class="rounded-circle mr-2 bg-primary text-white font-weight-bold d-inline-flex align-items-center justify-content-center" 
                     style="width: 32px; height: 32px; min-width: 32px; min-height: 32px; font-size: 14px; line-height: 1;">
                  {{ $initial }}
                </div>
              @endif

              <span class="nav-profile-name">
                {{ Auth::user()->nama ?? Auth::user()->name ?? '-' }}
              </span>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="{{ route('backend.logout') }}">
                <i class="mdi mdi-logout text-primary"></i>
                Keluar
              </a>
            </div>
          </li>
        </ul>

        <!-- Tombol Toggler Hamburger Menu Mobile -->
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- End Navbar -->

    <div class="container-fluid page-body-wrapper">
      
      <!-- Sidebar -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          
          {{-- 1. MENU DASHBOARD (Sembunyikan dari Ajudan) --}}
          @if (Auth::user()->role != '2' && Auth::user()->role != 'ajudan')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('backend.v_beranda.index') }}">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          @endif

          {{-- 2. MENU DATA USER (Khusus Super Admin & Admin / Role 0 & 1) --}}
          @if (in_array(Auth::user()->role, ['superadmin', 'admin', '0', '1']))
          <li class="nav-item">
            <a class="nav-link" href="{{ route('backend.user.index') }}">
              <i class="mdi mdi-account-multiple menu-icon"></i>
              <span class="menu-title">User</span>
            </a>
          </li>
          @endif

          {{-- 3. MENU LAYANAN SURAT (Bisa dilihat oleh Semua Role) --}}
          <li class="nav-item">
            <a class="nav-link" href="{{ route('backend.sambutan.index') }}">
              <i class="mdi mdi-file-document-outline menu-icon"></i>
              <span class="menu-title">Layanan Surat</span>
            </a>
          </li>

        </ul>
      </nav>
      <!-- End Sidebar -->

      <!-- Main Panel -->
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        <!-- content-wrapper ends -->

        <!-- Footer -->
        <footer class="footer">
          <div class="d-flex justify-content-center text-center">
            <span class="text-muted d-block">
              Copyright © {{ date('Y') }} <a href="#" target="_blank">Sekretariat Daerah Kabupaten Tegal</a>. All rights reserved.
            </span>
          </div>
        </footer>
        <!-- End Footer -->

      </div>
      <!-- main-panel ends -->

    </div>
    <!-- page-body-wrapper ends -->

  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="{{ asset('backend/majestic-master/vendors/base/vendor.bundle.base.js') }}"></script>

  <!-- Plugin js for this page-->
  <script src="{{ asset('backend/majestic-master/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('backend/majestic-master/vendors/datatables.net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('backend/majestic-master/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>

  <!-- inject:js -->
  <script src="{{ asset('backend/dist/js/off-canvas.js') }}"></script>
  <script src="{{ asset('backend/dist/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('backend/dist/js/template.js') }}"></script>

  <!-- Custom js for this page-->
  <script src="{{ asset('backend/dist/js/dashboard.js') }}"></script>
  <script src="{{ asset('backend/dist/js/data-table.js') }}"></script>
  <script src="{{ asset('backend/dist/js/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('backend/dist/js/dataTables.bootstrap4.js') }}"></script>

  <!-- SweetAlert -->
  <script src="{{ asset('sweetalert/sweetalert2.all.min.js') }}"></script>
  
  @if (session('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: "{{ session('success') }}"
    });
  </script>
  @endif

  <script type="text/javascript">
    // Konfirmasi delete
    $('.show_confirm').click(function(event) {
      var form = $(this).closest("form");
      event.preventDefault();
      Swal.fire({
        title: 'Konfirmasi Hapus Data?',
        html: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, dihapus',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  </script>

</body>
</html>