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

<!-- SECTION 1: Berjajar Sejajar (Total Surat & Total Surat Per Penyusun) -->
<div class="row mb-3">
    <!-- 1. Kotak Kiri: Total Keseluruhan Surat & Donut Chart -->
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card w-100">
            <div class="card-body">
                <h4 class="card-title mb-1">Total keseluruhan surat</h4>
                <p class="text-muted mb-4" style="font-size: 13px;">Semua jenis surat yang tercatat di sistem</p>
                
                <div class="row align-items-center">
                    <!-- Donut Chart -->
                    <div class="col-md-5 text-center">
                        <div style="position: relative; height: 210px; width: 100%;">
                            <canvas id="chartDonutSurat"></canvas>
                        </div>
                    </div>
                    <!-- Keterangan / List Nilai per Jenis Surat -->
                    <div class="col-md-7">
                        <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded" style="background: #f8f9fa;">
                            <div class="d-flex align-items-center">
                                <span class="rounded-circle mr-2" style="height: 10px; width: 10px; background-color: #6f42c1; display: inline-block;"></span>
                                <span class="text-muted font-weight-medium" style="font-size: 13px;">Surat Sambutan</span>
                            </div>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ $totalSuratSambutan ?? 0 }}</h4>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded" style="background: #f8f9fa;">
                            <div class="d-flex align-items-center">
                                <span class="rounded-circle mr-2" style="height: 10px; width: 10px; background-color: #dc3545; display: inline-block;"></span>
                                <span class="text-muted font-weight-medium" style="font-size: 13px;">Surat Keprotokolan</span>
                            </div>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ $totalSuratKeprotokolan ?? 0 }}</h4>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: #f8f9fa;">
                            <div class="d-flex align-items-center">
                                <span class="rounded-circle mr-2" style="height: 10px; width: 10px; background-color: #28a745; display: inline-block;"></span>
                                <span class="text-muted font-weight-medium" style="font-size: 13px;">Sambutan & Keprotokolan</span>
                            </div>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ $totalGabunganSurat ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Kotak Kanan: Total Surat Per Penyusun & Bar Chart -->
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card w-100">
            <div class="card-body">
                <h4 class="card-title mb-1">Total surat per penyusun</h4>
                <p class="text-muted mb-4" style="font-size: 13px;">Jumlah surat yang sudah dikerjakan setiap penyusun</p>
                <div style="position: relative; height: 210px; width: 100%;">
                    <canvas id="chartPenyusunCustom"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pemanggilan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Donut Chart untuk Total Keseluruhan Surat
        var ctxDonut = document.getElementById('chartDonutSurat');
        if (ctxDonut) {
            Chart.pluginService.register({
                beforeDraw: function(chart) {
                    if (chart.config.options.elements && chart.config.options.elements.center) {
                        var centerConfig = chart.config.options.elements.center;
                        var ctx = chart.chart.ctx;
                        var fontStyle = centerConfig.fontStyle || 'Arial';
                        var text = centerConfig.text;
                        var subText = centerConfig.subText || '';
                        
                        ctx.restore();
                        var fontSize = (centerConfig.fontSize || 26).toFixed(2);
                        ctx.font = "bold " + fontSize + "px " + fontStyle;
                        ctx.fillStyle = centerConfig.fontColor || '#333';
                        ctx.textBaseline = "middle";

                        var centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                        var centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2 - 8;

                        ctx.textAlign = "center";
                        ctx.fillText(text, centerX, centerY);

                        ctx.font = "11px " + fontStyle;
                        ctx.fillStyle = "#888888";
                        ctx.fillText(subText, centerX, centerY + 20);
                        ctx.save();
                    }
                }
            });

            var totalSemuaSurat = {{ $totalSambutan ?? 0 }};

            new Chart(ctxDonut.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Surat Sambutan', 'Surat Keprotokolan', 'Sambutan & Keprotokolan'],
                    datasets: [{
                        data: [
                            {{ $totalSuratSambutan ?? 0 }}, 
                            {{ $totalSuratKeprotokolan ?? 0 }}, 
                            {{ $totalGabunganSurat ?? 0 }}
                        ],
                        backgroundColor: ['#6f42c1', '#dc3545', '#28a745'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 75,
                    legend: {
                        display: false
                    },
                    elements: {
                        center: {
                            text: totalSemuaSurat.toString(),
                            subText: 'Total surat',
                            fontStyle: 'Poppins, sans-serif',
                            sidePadding: 20,
                            fontSize: 24,
                            fontColor: '#212529'
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var label = data.labels[tooltipItem.index] || '';
                                var value = dataset.data[tooltipItem.index];
                                return ' ' + label + ': ' + value;
                            }
                        }
                    }
                }
            });
        }

        // 2. Bar Chart untuk Total Surat Berdasarkan Penyusun
        var ctxPenyusun = document.getElementById('chartPenyusunCustom');
        if (ctxPenyusun) {
            new Chart(ctxPenyusun.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labelPenyusun ?? []) !!},
                    datasets: [{
                        label: 'Jumlah Surat Dikerjakan',
                        data: {!! json_encode($dataPenyusun ?? []) !!},
                        backgroundColor: '#2b70ff',
                        borderColor: '#2b70ff',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0
                            },
                            gridLines: {
                                drawBorder: false,
                                color: '#f2f2f2'
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });
        }
    });
</script>
@endsection