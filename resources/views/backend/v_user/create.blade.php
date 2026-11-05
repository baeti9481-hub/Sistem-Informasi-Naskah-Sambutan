@extends('backend.v_layouts.app')

@section('content')
<!-- contentAwal -->

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form class="form-horizontal" action="{{ route('backend.user.store') }}" method="post" enctype="multipart/form-data" id="userForm">
                    @csrf

                    <div class="card-body">
                        <h4 class="card-title"> {{$judul}} </h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Foto</label>
                                    <img class="foto-preview">
                                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewFoto()">
                                    @error('foto')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Hak Ases</label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="" {{ old('role') == '' ? 'selected' : '' }}>Pilih Hak Akses</option>
                                        <option value="1" {{ old('role') == '1' ? 'selected' : '' }}> Super Admin</option>
                                        <option value="0" {{ old('role') == '0' ? 'selected' : '' }}> Admin</option>
                                        <option value="2" {{ old('role') == '2' ? 'selected' : '' }}> Ajudan</option>
                                    </select>
                                    @error('role')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama">
                                    @error('nama')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email">
                                    @error('email')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password">
                                    
                                    <!-- KOTAK INDIKATOR SYARAT PASSWORD -->
                                    <div id="password-requirements" class="mt-2" style="font-size: 11px; background: #f8f9fa; padding: 10px; border-radius: 6px; border: 1px solid #e9ecef; display: none;">
                                        <p class="mb-1 font-weight-bold text-dark" style="font-size: 11px;">Kata sandi harus terdiri dari:</p>
                                        <ul style="list-style-type: none; padding-left: 0; margin-bottom: 0;">
                                            <li id="length" class="invalid" style="color: #6c757d; margin-bottom: 5px; position: relative; padding-left: 20px;">Minimal 8 karakter</li>
                                            <li id="capital" class="invalid" style="color: #6c757d; margin-bottom: 5px; position: relative; padding-left: 20px;">Menggunakan huruf kapital (A-Z)</li>
                                            <li id="lowercase" class="invalid" style="color: #6c757d; margin-bottom: 5px; position: relative; padding-left: 20px;">Menggunakan huruf kecil (a-z)</li>
                                            <li id="number" class="invalid" style="color: #6c757d; margin-bottom: 5px; position: relative; padding-left: 20px;">Menggunakan angka (0-9)</li>
                                            <li id="special" class="invalid" style="color: #6c757d; margin-bottom: 5px; position: relative; padding-left: 20px;">Menggunakan karakter khusus (!@#$%^&*)</li>
                                        </ul>
                                    </div>

                                    @error('password')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="passwordConfirmInput" class="form-control" placeholder="Konfirmasi Password">
                                    <!-- Notifikasi error password tidak sesuai -->
                                    <div id="password-match-error" class="invalid-feedback alert-danger" style="display: none;">
                                        Password tidak sesuai
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('backend.user.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tambahan Style CSS untuk Indikator Bulat Valid/Invalid -->
<style>
    #password-requirements li.invalid::before {
        content: "✕";
        position: absolute;
        left: 0;
        top: 0;
        width: 14px;
        height: 14px;
        background-color: #dc3545;
        color: #ffffff;
        border-radius: 50%;
        font-size: 9px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    #password-requirements li.valid {
        color: #28a745 !important;
    }
    #password-requirements li.valid::before {
        content: "✓";
        position: absolute;
        left: 0;
        top: 0;
        width: 14px;
        height: 14px;
        background-color: #28a745;
        color: #ffffff;
        border-radius: 50%;
        font-size: 9px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

<!-- Script JavaScript untuk Validasi Real-time & Konfirmasi Password -->
<script>
    const passwordInput = document.getElementById('passwordInput');
    const passwordConfirmInput = document.getElementById('passwordConfirmInput');
    const passwordMatchError = document.getElementById('password-match-error');
    const userForm = document.getElementById('userForm');
    
    const requirementsBox = document.getElementById('password-requirements');
    const lengthReq = document.getElementById('length');
    const capitalReq = document.getElementById('capital');
    const lowercaseReq = document.getElementById('lowercase');
    const numberReq = document.getElementById('number');
    const specialReq = document.getElementById('special');

    passwordInput.addEventListener('focus', function() {
        requirementsBox.style.display = 'block';
    });

    passwordInput.addEventListener('blur', function() {
        if (passwordInput.value === '') {
            requirementsBox.style.display = 'none';
        }
    });

    // Pengecekan real-time kekuatan password
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

        // 5. Cek Karakter Khusus (!@#$%^&*)
        if (/[!@#$%^&*(),.?":{}|<>]/.test(val)) {
            specialReq.classList.remove('invalid');
            specialReq.classList.add('valid');
        } else {
            specialReq.classList.remove('valid');
            specialReq.classList.add('invalid');
        }

        // Cek kecocokan konfirmasi password secara real-time jika konfirmasi sudah diisi
        checkPasswordMatch();
    });

    // Validasi kecocokan password saat mengetik di input konfirmasi
    passwordConfirmInput.addEventListener('keyup', function() {
        checkPasswordMatch();
    });

    function checkPasswordMatch() {
        if (passwordConfirmInput.value !== '' && passwordConfirmInput.value !== passwordInput.value) {
            passwordConfirmInput.classList.add('is-invalid');
            passwordMatchError.style.display = 'block';
        } else {
            passwordConfirmInput.classList.remove('is-invalid');
            passwordMatchError.style.display = 'none';
        }
    }

    // Cegah submit form jika password tidak cocok
    userForm.addEventListener('submit', function(e) {
        if (passwordConfirmInput.value !== passwordInput.value) {
            e.preventDefault();
            passwordConfirmInput.classList.add('is-invalid');
            passwordMatchError.style.display = 'block';
            passwordConfirmInput.focus();
        }
    });
</script>

<!-- contentAkhir -->
@endsection