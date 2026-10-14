@extends('backend.v_layouts.app')

@section('content')
<!-- Welcome Alert -->
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Dashboard</h4>
                <div class="alert alert-success mb-0" role="alert">
                    <h4 class="alert-heading font-weight-bold">Selamat Datang, {{ Auth::user()->nama ?? Auth::user()->name }}</h4>
                    <p class="mb-0">Dengan hak akses yang Anda miliki sebagai 
                        <b>
                            @if (Auth::user()->role == 1)
                                Super Admin
                            @elseif(Auth::user()->role == 0)
                                Admin
                            @else
                                User
                            @endif
                        </b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 1: Statistik Sistem (Disesuaikan dengan Menu Sidebar) -->
<div class="row mb-3">
    <!-- 1. Total User -->
    <div class="col-md-6 grid-margin stretch-card">
        <a href="{{ route('backend.user.index') }}" class="text-decoration-none w-100">
            <div class="card bg-primary text-white card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1">Total User</p>
                            <h2 class="mb-0 font-weight-bold">{{ $totalUser ?? 0 }}</h2>
                        </div>
                        <i class="mdi mdi-account-multiple mdi-36px"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- 2. Total Surat Sambutan (Manajemen Surat) -->
    <div class="col-md-6 grid-margin stretch-card">
        <a href="{{ route('backend.sambutan.index') }}" class="text-decoration-none w-100">
            <div class="card bg-warning text-dark card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1">Total Surat</p>
                            <h2 class="mb-0 font-weight-bold">{{ $totalSambutan ?? 0 }}</h2>
                        </div>
                        <i class="mdi mdi-file-document mdi-36px"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- SECTION 2: Grafik Surat Sambutan Bulanan -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Grafik Surat (Per Bulan)</h4>
                <div style="position: relative; height:320px; width:100%">
                    <canvas id="chartSambutanCustom"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pemanggilan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx2 = document.getElementById('chartSambutanCustom');
        if (ctx2) {
            new Chart(ctx2.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Jumlah Surat ',
                        data: {!! json_encode($dataBulananSambutan ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!},
                        borderColor: 'rgba(23, 162, 184, 1)',
                        backgroundColor: 'rgba(23, 162, 184, 0.15)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    scales: { 
                        yAxes: [{ 
                            ticks: { 
                                beginAtZero: true,
                                precision: 0 
                            } 
                        }] 
                    } 
                }
            });
        }
    });
</script>
@endsection