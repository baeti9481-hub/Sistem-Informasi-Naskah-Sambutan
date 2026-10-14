<!DOCTYPE html>
<html lang="id">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>LUPA PASSWORD - DATA ACARA</title>
  
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


              <h4>Reset Password</h4>
              <h6 class="font-weight-light">Masukkan email dan password baru Anda.</h6>

              <!-- Tampilkan Pesan Error Jika Ada Validation Error -->
              @if ($errors->any())
                <div class="alert alert-danger mt-2 py-2 small" role="alert">
                  <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

             <!-- FORM LUPA PASSWORD -->
<form class="pt-3" action="{{ route('lupa_password.proses') }}" method="POST">
  @csrf

  <!-- Email Terdaftar -->
  <div class="form-group mb-3">
    <label for="email" class="font-weight-bold text-dark small">Email Terdaftar</label>
    <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Masukkan email Anda" value="{{ old('email') }}" required>
  </div>
  
  <!-- Password Baru -->
  <div class="form-group mb-3">
    <label for="password" class="font-weight-bold text-dark small">Password Baru</label>
    <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Masukkan password baru" required>
    <small class="form-text text-muted">Minimal 6 karakter.</small>
  </div>

  <!-- Konfirmasi Password Baru -->
  <div class="form-group mb-3">
    <label for="password_confirmation" class="font-weight-bold text-dark small">Konfirmasi Password Baru</label>
    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" placeholder="Ulangi password baru" required>
  </div>

  <div class="mt-3">
    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Simpan Password Baru</button>
  </div>

  <div class="text-center mt-4">
    <a href="{{ route('login') }}" class="auth-link text-black">Kembali ke Login</a>
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