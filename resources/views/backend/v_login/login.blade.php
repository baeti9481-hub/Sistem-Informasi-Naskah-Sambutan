<!DOCTYPE html>
<html lang="id">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DATA ACARA</title>
  
  <!-- Google Fonts (Poppins) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('backend/majestic-master/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/majestic-master/vendors/base/vendor.bundle.base.css') }}">
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('backend/dist/css/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('backend/images/logo.jpeg') }}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              
              <!-- Brand Logo(Gambar Logo) -->
<div class="brand-logo text-center mb-4">
  <a href="{{ url('/') }}" class="text-decoration-none">
    <img src="{{ asset('backend/images/sinas2.png') }}" 
         alt="Logo sinas" 
         style="max-height: 80px; width: auto;" 
         class="img-fluid">
  </a>
</div>

              <h4>Hello! Selamat Datang</h4>
              <h6 class="font-weight-light">Masuk Untuk Melanjutkan.</h6>

              <!-- NOTIFIKASI SUKSES (Seteah reset password) -->
              @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                  {{ session('success') }}
                </div>
              @endif

              <!-- NOTIFIKASI ERROR AUTHENTICATE (Email/PW salah / Akun nonaktif) -->
              @if (session('loginError'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                  {{ session('loginError') }}
                </div>
              @endif

              <!-- NOTIFIKASI VALIDASI FORM (Jika email/pw kosong) -->
              @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                  <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <!-- FORM LOGIN -->
              <form class="pt-3" action="{{ route('login.proses') }}" method="POST">
                @csrf <!-- Token Keamanan Laravel (Wajib) -->

                <div class="form-group">
                  <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="exampleInputEmail1" placeholder="Email" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-group">
                  <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="exampleInputPassword1" placeholder="Password" required>
                </div>

                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Login</button>
                </div>

                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" name="remember" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="{{ route('lupa_password') }}" class="auth-link text-black">Lupa password?</a>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
  <!-- plugins:js -->
  <script src="{{ asset('backend/majestic-master/vendors/base/vendor.bundle.base.js') }}"></script>
  <!-- inject:js -->
  <script src="{{ asset('backend/dist/js/off-canvas.js') }}"></script>
  <script src="{{ asset('backend/dist/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('backend/dist/js/template.js') }}"></script>

</body>

</html>