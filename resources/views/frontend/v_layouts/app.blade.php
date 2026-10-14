<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SINAS')</title>

    <!-- Google Fonts (Poppins & Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Font Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SCRIPT INLINE: Cegah Flicker saat reload halaman -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <style>
        /* ==========================================
           1. VARIABEL TEMA (DARK & LIGHT)
           ========================================== */
        :root[data-theme="dark"] {
            --bg-body: #0f172a;
            --bg-navbar: #0f172a;
            --bg-hero: radial-gradient(circle at top center, #1e3a8a 0%, #0f172a 70%);
            --bg-footer: #1e293b;
            --text-main: #ffffff;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
            --btn-outline-bg: rgba(255, 255, 255, 0.05);
            --btn-outline-border: rgba(255, 255, 255, 0.2);
            --btn-outline-text: #ffffff;
            --badge-bg: rgba(59, 130, 246, 0.15);
            --badge-text: #60a5fa;
            --badge-border: rgba(59, 130, 246, 0.3);
            --toggle-btn-color: #facc15;
        }

        :root[data-theme="light"] {
            --bg-body: #f8fafc;
            --bg-navbar: #ffffff;
            --bg-hero: radial-gradient(circle at top center, #e0f2fe 0%, #f8fafc 70%);
            --bg-footer: #0f172a;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: rgba(0, 0, 0, 0.08);
            --btn-outline-bg: #ffffff;
            --btn-outline-border: #cbd5e1;
            --btn-outline-text: #0f172a;
            --badge-bg: rgba(37, 99, 235, 0.1);
            --badge-text: #2563eb;
            --badge-border: rgba(37, 99, 235, 0.2);
            --toggle-btn-color: #6366f1;
        }

        /* ==========================================
           2. TAMPILAN UMUM (STYLING)
           ========================================== */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body) !important;
            color: var(--text-main) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Navbar Custom & Responsive Logo */
        .navbar-custom {
            background-color: var(--bg-navbar) !important;
            border-bottom: 1px solid var(--border-color) !important;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        /* STYLING LOGO RESPONSIP HP & DESKTOP */
        .navbar-logo {
            height: 45px; /* Ukuran Proporsional di Layar HP */
            width: auto;
            object-fit: contain;
            transition: height 0.2s ease-in-out;
        }

       @media (min-width: 992px) {
    .navbar-logo {
        height: 75px; 
        margin-top: -6px;    /* Menggeser gambar sedikit ke atas agar pas */
        margin-bottom: -6px; /* Mencegah background navbar ikut melebar */
    }
}

        .nav-link-custom {
            color: var(--text-muted) !important;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 8px 16px !important;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover {
            color: var(--text-main) !important;
        }

        .nav-link-custom.active {
            color: #3b82f6 !important;
            background-color: rgba(59, 130, 246, 0.1);
        }

        /* Styling Dropdown Adaptif Tema */
        .dropdown-menu-custom {
            background-color: var(--bg-navbar) !important;
            border: 1px solid var(--border-color) !important;
            padding: 0.5rem 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .dropdown-menu-custom .dropdown-item {
            color: var(--text-main) !important;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .dropdown-menu-custom .dropdown-item:hover,
        .dropdown-menu-custom .dropdown-item:focus {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: var(--text-main) !important;
        }

        :root[data-theme="light"] .dropdown-menu-custom .dropdown-item:hover,
        :root[data-theme="light"] .dropdown-menu-custom .dropdown-item:focus {
            background-color: rgba(0, 0, 0, 0.05) !important;
        }

        .dropdown-menu-custom .dropdown-item.active {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        /* Hero Banner */
        .hero-banner {
            background: var(--bg-hero) !important;
            padding: 80px 0 90px;
            position: relative;
            transition: background 0.3s ease;
        }

        .badge-portal {
            background-color: var(--badge-bg);
            color: var(--badge-text);
            border: 1px solid var(--badge-border);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-main);
            letter-spacing: -1px;
            transition: color 0.3s ease;
        }

        .text-gradient-blue {
            background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            color: var(--text-muted);
            font-size: 1.05rem;
            max-width: 720px;
            margin: 0 auto;
            line-height: 1.6;
            transition: color 0.3s ease;
        }

        /* Buttons */
        .btn-blue-main {
            background-color: #2563eb;
            color: #ffffff !important;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 8px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-blue-main:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-outline-custom {
            background-color: var(--btn-outline-bg);
            color: var(--btn-outline-text) !important;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 8px;
            border: 1px solid var(--btn-outline-border);
            transition: all 0.2s ease;
        }

        .btn-outline-custom:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }

        /* Toggle Button Theme */
        .btn-toggle-theme {
            background: transparent;
            border: none;
            color: var(--toggle-btn-color);
            padding: 6px 10px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s ease, color 0.3s ease;
        }

        .btn-toggle-theme:hover {
            transform: scale(1.15);
        }

        /* Footer */
        .footer-custom {
            background-color: var(--bg-footer) !important;
            color: #cbd5e1;
            transition: background-color 0.3s ease;
        }

        .hover-white:hover {
            color: #ffffff !important;
            transition: color 0.2s ease-in-out;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- HEADER / NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom py-2 sticky-top">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center me-4 py-0" href="{{ url('/') }}" style="text-decoration: none;">
            <img src="{{ asset('backend/images/sinas2.png') }}" 
                 alt="Logo Si-Sambut" 
                 class="navbar-logo d-inline-block align-text-top">
        </a>

        <!-- Toggler Seluler -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars text-primary fs-4"></i>
        </button>

        <!-- Menu Navigasi -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav gap-2 align-items-center">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                </li>
                
                <!-- DROPDOWN LAYANAN SURAT -->
                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-custom dropdown-toggle {{ Request::routeIs('frontend.sambutan*') || Request::routeIs('frontend.keprotokolan*') ? 'active' : '' }}" 
                       href="#" 
                       id="navbarDropdownLayananSurat" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false">
                        Layanan Surat
                    </a>
                    <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="navbarDropdownLayananSurat">
                        <li>
                            <a class="dropdown-item {{ Request::routeIs('frontend.sambutan*') ? 'active' : '' }}" href="{{ route('frontend.sambutan') }}">
                                Surat Sambutan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ Request::routeIs('frontend.keprotokolan*') ? 'active' : '' }}" href="{{ route('frontend.keprotokolan') }}">
                                Keprotokolan
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- TOMBOL TOGGLE TEMA -->
                <li class="nav-item ms-lg-2">
                    <button class="btn-toggle-theme" id="themeToggleBtn" type="button" aria-label="Ganti Mode Tema">
                        <i class="bi bi-sun-fill fs-5" id="themeIcon"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
@if(Request::is('/'))
    {{-- BERANDA --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>
@else
    {{-- HALAMAN LAIN --}}
    <main class="flex-grow-1 my-4">
        <div class="container">
            @yield('content')
        </div>
    </main>
@endif

<!-- FOOTER UTAMA -->
<footer class="footer-custom text-white pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row g-4 pb-4 border-bottom border-secondary">
            <!-- About & Branding -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-building-gear fs-3 text-primary me-2"></i>
                    <h5 class="fw-bold mb-0 text-white">SINAS</h5>
                </div>
                <p class="text-white-50 small mb-3">
                    Sistem Informasi Layanan Surat Sambutan dan Keprotokolan Resmi Publik Sekretariat Daerah. Menjawab kebutuhan transparansi dan efisiensi layanan instansi.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle px-2 py-1" title="Website"><i class="bi bi-globe"></i></a>
                    <a href="https://wa.me/#" target="_blank" class="btn btn-sm btn-outline-success rounded-circle px-2 py-1" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <!-- Kontak Kami -->
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold mb-3 text-white text-uppercase tracking-wider">Kontak Kami</h6>
                <ul class="list-unstyled text-white-50 small mb-0 d-flex flex-column gap-2">
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt-fill text-primary mt-1"></i>
                        <span>Bagian Protokol & Komunikasi Pimpinan, Gedung Sekretariat Daerah Kabupaten Tegal</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-fill text-primary"></i>
                        <a href="mailto:protokol@sisambut.go.id" class="text-white-50 text-decoration-none">protokol@SINAS.go.id</a>
                    </li>
                </ul>
            </div>

            <!-- Navigasi & Layanan -->
            <div class="col-lg-4 col-md-12">
                <h6 class="fw-bold mb-3 text-white text-uppercase tracking-wider">Navigasi & Layanan</h6>
                <ul class="list-unstyled text-white-50 small mb-0 d-flex flex-column gap-2">
                    <li>
                        <a href="{{ route('frontend.sambutan') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right me-1 text-primary"></i> Layanan Surat Sambutan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.keprotokolan') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right me-1 text-primary"></i> Layanan Keprotokolan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right me-1 text-primary"></i> Panduan & Prosedur 
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- COPYRIGHT & SUB FOOTER -->
        <div class="row pt-3 align-items-center small text-white-50">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                &copy; {{ date('Y') }} <strong class="text-white">SINAS</strong>. BP.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-white-50 text-decoration-none me-3 hover-white">Syarat & Ketentuan</a>
                <a href="#" class="text-white-50 text-decoration-none hover-white">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- SCRIPT TOGGLE TEMA -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById("themeToggleBtn");
        const themeIcon = document.getElementById("themeIcon");
        const root = document.documentElement;

        function updateIcon(theme) {
            if (theme === "dark") {
                themeIcon.className = "bi bi-sun-fill fs-5";
            } else {
                themeIcon.className = "bi bi-moon-stars-fill fs-5";
            }
        }

        const currentTheme = root.getAttribute("data-theme") || "dark";
        updateIcon(currentTheme);

        toggleBtn.addEventListener("click", function () {
            const activeTheme = root.getAttribute("data-theme");
            const newTheme = activeTheme === "dark" ? "light" : "dark";

            root.setAttribute("data-theme", newTheme);
            localStorage.setItem("theme", newTheme);
            updateIcon(newTheme);
        });
    });
</script>
</body>
</html>