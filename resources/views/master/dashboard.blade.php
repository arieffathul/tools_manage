@extends('layouts.app')
@php $currentPage = 'dashboard'; @endphp

@section('title', 'Dashboard | Tools Management')
@section('content')
    <main class="app-main">
        <!-- Header -->
        <div class="app-content-header py-4 mb-4 bg-white border-bottom">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1 text-primary fw-semibold">
                            <i class="bi bi-bar-chart-line-fill me-2 text-secondary"></i>Dashboard Admin
                        </h3>
                        <p class="text-muted mb-0">
                            Hai <strong>{{ Auth::user()->name }}</strong>, Pantau aktivitas peminjaman tools STO03</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-secondary border">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="app-content mb-4">
            <div class="container-fluid">
                <div class="row g-3">
                    <!-- Total Tools -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-start border-primary border-4 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted mb-2">Total Tools</p>
                                        <h3 class="fw-bold mb-0">156</h3>
                                        <small class="text-success">
                                            <i class="bi bi-arrow-up"></i> 12% dari bulan lalu
                                        </small>
                                    </div>
                                    <div class="bg-primary bg-opacity-10 p-2 rounded">
                                        <i class="bi bi-tools text-primary fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sedang Dipinjam -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-start border-warning border-4 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted mb-2">Sedang Dipinjam</p>
                                        <h3 class="fw-bold mb-0 text-warning">28</h3>
                                        <small class="text-muted">
                                            18 belum kembali
                                        </small>
                                    </div>
                                    <div class="bg-warning bg-opacity-10 p-2 rounded">
                                        <i class="bi bi-box-arrow-in-right text-warning fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengembalian Hari Ini -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-start border-success border-4 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted mb-2">Dikembalikan Hari Ini</p>
                                        <h3 class="fw-bold mb-0 text-success">15</h3>
                                        <small class="text-success">
                                            <i class="bi bi-check-circle"></i> 2 menunggu verifikasi
                                        </small>
                                    </div>
                                    <div class="bg-success bg-opacity-10 p-2 rounded">
                                        <i class="bi bi-box-arrow-left text-success fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Telat Kembali -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-start border-danger border-4 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted mb-2">Telat Kembali</p>
                                        <h3 class="fw-bold mb-0 text-danger">7</h3>
                                        <small class="text-danger">
                                            <i class="bi bi-exclamation-triangle"></i> +2 dari kemarin
                                        </small>
                                    </div>
                                    <div class="bg-danger bg-opacity-10 p-2 rounded">
                                        <i class="bi bi-clock-history text-danger fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik dan Aktivitas -->
        <div class="app-content">
            <div class="container-fluid">
                <div class="row g-3">
                    <!-- Grafik Peminjaman Mingguan -->
                    <div class="col-xl-8">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="bi bi-graph-up me-2 text-primary"></i>
                                    Tren Peminjaman 7 Hari Terakhir
                                </h5>
                            </div>
                            <div class="card-body p-3">
                                <div style="height: 300px;">
                                    <canvas id="weeklyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aktivitas Terbaru -->
                    <div class="col-xl-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="bi bi-activity me-2 text-success"></i>
                                    Aktivitas Terbaru
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @php
                                        $activities = [
                                            [
                                                'type' => 'return',
                                                'name' => 'Budi Santoso',
                                                'tool' => 'Impact Wrench',
                                                'time' => 2,
                                            ],
                                            [
                                                'type' => 'borrow',
                                                'name' => 'Sari Dewi',
                                                'tool' => 'Multimeter Digital',
                                                'time' => 4,
                                            ],
                                            [
                                                'type' => 'late',
                                                'name' => 'Andi Wijaya',
                                                'tool' => 'Drill Machine',
                                                'time' => 6,
                                            ],
                                            [
                                                'type' => 'return',
                                                'name' => 'Rina Amelia',
                                                'tool' => 'Socket Set',
                                                'time' => 8,
                                            ],
                                            [
                                                'type' => 'borrow',
                                                'name' => 'Fajar Pratama',
                                                'tool' => 'Thermal Camera',
                                                'time' => 10,
                                            ],
                                            [
                                                'type' => 'late',
                                                'name' => 'Doni Saputra',
                                                'tool' => 'Cable Tester',
                                                'time' => 12,
                                            ],
                                        ];
                                    @endphp
                                    @foreach ($activities as $activity)
                                        <div class="list-group-item border-0 py-3 px-3">
                                            <div class="d-flex">
                                                <div class="shrink-0">
                                                    @if ($activity['type'] === 'return')
                                                        <i class="bi bi-box-arrow-left text-success fs-5"></i>
                                                    @elseif($activity['type'] === 'borrow')
                                                        <i class="bi bi-box-arrow-in-right text-primary fs-5"></i>
                                                    @else
                                                        <i class="bi bi-exclamation-triangle text-warning fs-5"></i>
                                                    @endif
                                                </div>
                                                <div class="grow ms-3">
                                                    <h6 class="mb-1">
                                                        @if ($activity['type'] === 'return')
                                                            Pengembalian Tool
                                                        @elseif($activity['type'] === 'borrow')
                                                            Peminjaman Baru
                                                        @else
                                                            Peringatan Telat
                                                        @endif
                                                    </h6>
                                                    <p class="text-muted small mb-1">
                                                        <strong>{{ $activity['name'] }}</strong>
                                                        @if ($activity['type'] === 'return')
                                                            mengembalikan {{ $activity['tool'] }}
                                                        @elseif($activity['type'] === 'borrow')
                                                            meminjam {{ $activity['tool'] }}
                                                        @else
                                                            telat mengembalikan {{ $activity['tool'] }}
                                                        @endif
                                                    </p>
                                                    <small class="text-muted">
                                                        {{ now()->subHours($activity['time'])->locale('id')->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tool Paling Sering Dipinjam -->
                    <div class="col-xl-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="bi bi-star-fill me-2 text-warning"></i>
                                    Tool Paling Sering Dipinjam
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nama Tool</th>
                                                <th>Kode</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Rating</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $popularTools = [
                                                    [
                                                        'name' => 'Multimeter Digital',
                                                        'code' => 'MTD-001',
                                                        'count' => 45,
                                                        'rating' => 4.8,
                                                    ],
                                                    [
                                                        'name' => 'Impact Wrench',
                                                        'code' => 'IMP-023',
                                                        'count' => 38,
                                                        'rating' => 4.7,
                                                    ],
                                                    [
                                                        'name' => 'Drill Machine',
                                                        'code' => 'DRL-045',
                                                        'count' => 32,
                                                        'rating' => 4.5,
                                                    ],
                                                    [
                                                        'name' => 'Thermal Camera',
                                                        'code' => 'THC-012',
                                                        'count' => 28,
                                                        'rating' => 4.6,
                                                    ],
                                                    [
                                                        'name' => 'Cable Tester',
                                                        'code' => 'CBT-078',
                                                        'count' => 25,
                                                        'rating' => 4.3,
                                                    ],
                                                ];
                                            @endphp
                                            @foreach ($popularTools as $tool)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-tools text-primary me-2"></i>
                                                            {{ $tool['name'] }}
                                                        </div>
                                                    </td>
                                                    <td><code class="bg-light px-1 rounded">{{ $tool['code'] }}</code></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                                            {{ $tool['count'] }}x
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-warning">{{ $tool['rating'] }}</span>
                                                        <i class="bi bi-star-fill text-warning ms-1 small"></i>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-xl-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="bi bi-lightning-fill me-2 text-info"></i>
                                    Quick Actions
                                </h5>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-2">

                                    <div class="col-md-6">
                                        <a href="{{ route('tool.index') }}"
                                            class="card border text-decoration-none h-100 hover-shadow">
                                            <div class="card-body text-center p-3">
                                                <div
                                                    class="bg-warning bg-opacity-10 rounded-circle p-2 d-inline-block mb-2">
                                                    <i class="bi bi-search text-warning fs-4"></i>
                                                </div>
                                                <h6 class="fw-semibold mb-1">Cari Tool</h6>
                                                <p class="text-muted small mb-0">Cari dan kelola inventory</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('borrow.index') }}"
                                            class="card border text-decoration-none h-100 hover-shadow">
                                            <div class="card-body text-center p-3">
                                                <div
                                                    class="bg-danger bg-opacity-10 rounded-circle p-2 d-inline-block mb-2">
                                                    <i class="bi bi-clock-history text-danger fs-4"></i>
                                                </div>
                                                <h6 class="fw-semibold mb-1">Monitor Telat</h6>
                                                <p class="text-muted small mb-0">Lihat peminjaman yang telat</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
    <style>
        .hover-shadow:hover {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            transform: translateY(-2px);
            transition: all 0.2s;
        }

        .border-4 {
            border-width: 4px !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik Tren Peminjaman
            const ctx = document.getElementById('weeklyChart').getContext('2d');
            const weeklyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Peminjaman',
                        data: [12, 19, 15, 25, 22, 18, 10],
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }, {
                        label: 'Pengembalian',
                        data: [8, 12, 10, 18, 15, 12, 5],
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
