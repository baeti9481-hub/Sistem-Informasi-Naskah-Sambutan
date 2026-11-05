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

              <h4>Selamat Datang!</h4>
              <h6 class="font-weight-light">Masuk Untuk Melanjutkan.</h6>

              <!-- NOTIFIKASI SUKSES -->
              @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                  {{ session('success') }}
                </div>
              @endif

              <!-- NOTIFIKASI ERROR AUTHENTICATE -->
              @if (session('loginError'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                  {{ session('loginError') }}
                </div>
              @endif

              <!-- NOTIFIKASI VALIDASI FORM -->
              @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                  <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <!-- PESAN LOCKOUT 30 DETIK -->
              <div id="lockout-message" class="alert alert-danger mt-3" style="display: none;">
                Terlalu banyak kesalahan captcha (3x). Mohon tunggu <span id="countdown-timer">30</span> detik sebelum mencoba kembali.
              </div>

              <!-- FORM LOGIN -->
              <form class="pt-3" action="{{ route('login.proses') }}" method="POST" id="loginForm">
                @csrf <!-- Token Keamanan Laravel (Wajib) -->

                <div class="form-group">
                  <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="exampleInputEmail1" placeholder="Email" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-group">
                  <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="exampleInputPassword1" placeholder="Password" required>
                </div>

                <!-- KODE KEAMANAN (CAPTCHA CANVAS) -->
                <div class="form-group">
                  <label class="font-weight-bold text-muted" style="font-size: 12px;">KODE KEAMANAN</label>
                  <div class="d-flex align-items-center mb-2">
                    <canvas id="captcha-canvas" width="150" height="45" style="border: 1px solid #ced4da; border-radius: 4px; background: #f8f9fa;"></canvas>
                    <button type="button" class="btn btn-outline-secondary ml-2" id="refresh-captcha" title="Acak Ulang Captcha" style="height: 45px;">
                      <i class="mdi mdi-refresh"></i>
                    </button>
                  </div>
                  <input type="text" id="captcha-input" class="form-control form-control-lg" placeholder="Masukkan 4 karakter di atas" maxlength="4" required autocomplete="off">
                  <small id="captcha-error" class="text-danger mt-1" style="display: none; font-size: 11px;"></small>
                </div>

                <div class="mt-3">
                  <button type="submit" id="loginBtn" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Login</button>
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

  <!-- SCRIPT JAVASCRIPT UNTUK CANVAS CAPTCHA & LOCKOUT 30 DETIK -->
  <script>
    const captchaCanvas = document.getElementById('captcha-canvas');
    const ctx = captchaCanvas.getContext('2d');
    const captchaInput = document.getElementById('captcha-input');
    const refreshCaptchaBtn = document.getElementById('refresh-captcha');
    const captchaError = document.getElementById('captcha-error');
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const lockoutMessage = document.getElementById('lockout-message');
    const countdownTimerSpan = document.getElementById('countdown-timer');
    const emailInput = document.getElementById('exampleInputEmail1');
    const passwordInput = document.getElementById('exampleInputPassword1');
    const rememberCheckbox = document.querySelector('input[name="remember"]');

    let generatedCaptcha = '';

    // Generate 4 karakter kombinasi huruf besar, huruf kecil, dan angka
    function generateCaptchaText() {
      const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      let result = '';
      for (let i = 0; i < 4; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      return result;
    }

    // Render gambar captcha ke elemen Canvas dengan efek coretan/garis acak
    function drawCaptcha() {
      generatedCaptcha = generateCaptchaText();
      ctx.clearRect(0, 0, captchaCanvas.width, captchaCanvas.height);

      // Background warna abu-abu muda
      ctx.fillStyle = '#f8f9fa';
      ctx.fillRect(0, 0, captchaCanvas.width, captchaCanvas.height);

      // Tambahkan garis-garis coretan/noise agar agak susah dibaca bot
      for (let i = 0; i < 5; i++) {
        ctx.strokeStyle = getRandomColor();
        ctx.beginPath();
        ctx.moveTo(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height);
        ctx.lineTo(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height);
        ctx.stroke();
      }

      // Tambahkan titik-titik noise
      for (let i = 0; i < 30; i++) {
        ctx.fillStyle = getRandomColor();
        ctx.fillRect(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height, 2, 2);
      }

      // Tulis teks 4 karakter dengan posisi dan rotasi acak
      ctx.font = 'bold 24px "Poppins", sans-serif';
      let x = 20;
      for (let i = 0; i < generatedCaptcha.length; i++) {
        ctx.save();
        ctx.translate(x, 30);
        
        // Rotasi acak tiap huruf (-15 sampai 15 derajat)
        let angle = (Math.random() - 0.5) * 0.4;
        ctx.rotate(angle);

        ctx.fillStyle = getRandomColor();
        ctx.fillText(generatedCaptcha[i], 0, 0);
        ctx.restore();

        x += 30; // Jarak antar karakter
      }

      captchaInput.value = '';
      captchaError.style.display = 'none';
    }

    function getRandomColor() {
      const letters = '0123456789ABCDEF';
      let color = '#';
      for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 6)];
      }
      return color;
    }

    drawCaptcha();

    refreshCaptchaBtn.addEventListener('click', function() {
      drawCaptcha();
    });

    // Cek status lockout dari localStorage
    let failedAttempts = parseInt(localStorage.getItem('login_failed_attempts') || '0');
    let lockoutTime = parseInt(localStorage.getItem('login_lockout_time') || '0');

    function checkLockoutStatus() {
      const now = Date.now();
      if (lockoutTime && now < lockoutTime) {
        const remainingSeconds = Math.ceil((lockoutTime - now) / 1000);
        setLockedState(true, remainingSeconds);
      } else if (lockoutTime && now >= lockoutTime) {
        localStorage.removeItem('login_lockout_time');
        localStorage.setItem('login_failed_attempts', '0');
        failedAttempts = 0;
        setLockedState(false);
      }
    }

    function setLockedState(isLocked, seconds = 30) {
      if (isLocked) {
        loginBtn.disabled = true;
        captchaInput.disabled = true;
        emailInput.disabled = true;
        passwordInput.disabled = true;
        if (rememberCheckbox) rememberCheckbox.disabled = true;
        lockoutMessage.style.display = 'block';
        
        let timeLeft = seconds;
        countdownTimerSpan.textContent = timeLeft;
        
        const timerInterval = setInterval(() => {
          timeLeft--;
          countdownTimerSpan.textContent = timeLeft;
          if (timeLeft <= 0) {
            clearInterval(timerInterval);
            localStorage.removeItem('login_lockout_time');
            localStorage.setItem('login_failed_attempts', '0');
            setLockedState(false);
          }
        }, 1000);
      } else {
        loginBtn.disabled = false;
        captchaInput.disabled = false;
        emailInput.disabled = false;
        passwordInput.disabled = false;
        if (rememberCheckbox) rememberCheckbox.disabled = false;
        lockoutMessage.style.display = 'none';
        drawCaptcha();
      }
    }

    checkLockoutStatus();

    // Validasi ketika form disubmit
    loginForm.addEventListener('submit', function(e) {
      const now = Date.now();
      if (lockoutTime && now < lockoutTime) {
        e.preventDefault();
        return;
      }

      // Periksa kecocokan captcha (Case-sensitive / memperhatikan huruf besar & kecil)
      if (captchaInput.value !== generatedCaptcha) {
        e.preventDefault();
        failedAttempts++;
        localStorage.setItem('login_failed_attempts', failedAttempts);
        
        captchaError.textContent = 'Kode Captcha salah! (Percobaan salah ke-' + failedAttempts + ' dari 3)';
        captchaError.style.display = 'block';
        
        if (failedAttempts >= 3) {
          const blockUntil = Date.now() + 30 * 1000; // 30 detik
          localStorage.setItem('login_lockout_time', blockUntil);
          setLockedState(true, 30);
        } else {
          drawCaptcha();
        }
      } else {
        // Jika captcha benar, reset percobaan gagal
        localStorage.setItem('login_failed_attempts', '0');
      }
    });
  </script>

</body>

</html>