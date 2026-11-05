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

  <style>
    /* Styling tambahan untuk indikator kekuatan password bentuk lingkaran */
    #password-requirements {
      font-size: 11px;
      background: #f8f9fa;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #e9ecef;
      display: none; /* Muncul saat user mulai mengetik */
    }
    #password-requirements ul {
      list-style-type: none;
      padding-left: 0;
      margin-bottom: 0;
    }
    #password-requirements li {
      color: #6c757d; /* Warna abu-abu default (belum terpenuhi) */
      margin-bottom: 5px;
      line-height: 1.3;
      position: relative;
      padding-left: 20px;
    }
    /* Ikon bulat silang untuk kondisi invalid */
    #password-requirements li.invalid::before {
      content: "✕";
      position: absolute;
      left: 0;
      top: 0;
      width: 14px;
      height: 14px;
      background-color: #dc3545; /* Lingkaran merah */
      color: #ffffff;
      border-radius: 50%;
      font-size: 9px;
      font-weight: bold;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    /* Ikon bulat centang untuk kondisi valid */
    #password-requirements li.valid {
      color: #28a745;
    }
    #password-requirements li.valid::before {
      content: "✓";
      position: absolute;
      left: 0;
      top: 0;
      width: 14px;
      height: 14px;
      background-color: #28a745; /* Lingkaran hijau */
      color: #ffffff;
      border-radius: 50%;
      font-size: 9px;
      font-weight: bold;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              
              <!-- Brand Logo (Gambar Logo) -->
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
                  
                  <!-- KOTAK INDIKATOR SYARAT PASSWORD -->
                  <div id="password-requirements" class="mt-2">
                    <p class="mb-1 font-weight-bold text-dark" style="font-size: 11px;">Kata sandi harus terdiri dari:</p>
                    <ul>
                      <li id="length" class="invalid">Minimal 8 karakter</li>
                      <li id="capital" class="invalid">Menggunakan huruf kapital (A-Z)</li>
                      <li id="lowercase" class="invalid">Menggunakan huruf kecil (a-z)</li>
                      <li id="number" class="invalid">Menggunakan angka (0-9)</li>
                      <li id="special" class="invalid">Menggunakan karakter khusus (!@#$%^&*)</li>
                    </ul>
                  </div>
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

  <!-- SCRIPT JAVASCRIPT UNTUK CEK KELENGKAPAN SYARAT PASSWORD -->
  <script>
    const passwordInput = document.getElementById('password');
    const requirementsBox = document.getElementById('password-requirements');
    const lengthReq = document.getElementById('length');
    const capitalReq = document.getElementById('capital');
    const lowercaseReq = document.getElementById('lowercase');
    const numberReq = document.getElementById('number');
    const specialReq = document.getElementById('special');

    // Munculkan kotak saat kolom password diklik / mulai diisi
    passwordInput.addEventListener('focus', function() {
      requirementsBox.style.display = 'block';
    });

    // Sembunyikan jika kosong dan user keluar dari input
    passwordInput.addEventListener('blur', function() {
      if (passwordInput.value === '') {
        requirementsBox.style.display = 'none';
      }
    });

    // Pengecekan real-time saat user mengetik
    passwordInput.addEventListener('keyup', function() {
      const val = passwordInput.value;

      // 1. Cek Minimal 8 Karakter
      if (val.length >= 8) {
        lengthReq.classList.remove('invalid');
        lengthReq.classList.add('valid');
      } else {
        lengthReq.classList.remove('valid');
        lengthReq.classList.add('invalid');
      }

      // 2. Cek Huruf Kapital (A-Z)
      if (/[A-Z]/.test(val)) {
        capitalReq.classList.remove('invalid');
        capitalReq.classList.add('valid');
      } else {
        capitalReq.classList.remove('valid');
        capitalReq.classList.add('invalid');
      }

      // 3. Cek Huruf Kecil (a-z)
      if (/[a-z]/.test(val)) {
        lowercaseReq.classList.remove('invalid');
        lowercaseReq.classList.add('valid');
      } else {
        lowercaseReq.classList.remove('valid');
        lowercaseReq.classList.add('invalid');
      }

      // 4. Cek Angka (0-9)
      if (/[0-9]/.test(val)) {
        numberReq.classList.remove('invalid');
        numberReq.classList.add('valid');
      } else {
        numberReq.classList.remove('valid');
        numberReq.classList.add('invalid');
      }

      // 5. Cek Karakter Khusus (!@#$%^&* dll)
      if (/[!@#$%^&*(),.?":{}|<>]/.test(val)) {
        specialReq.classList.remove('invalid');
        specialReq.classList.add('valid');
      } else {
        specialReq.classList.remove('valid');
        specialReq.classList.add('invalid');
      }
    });
  </script>

</body>

</html>