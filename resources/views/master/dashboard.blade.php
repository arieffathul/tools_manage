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
                            Pantau aktivitas peminjaman tools STO03</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-secondary border" style="font-size: 15px;">
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
                        <a href="{{ route('tool.index') }}" class="text-decoration-none">
                            <div class="card border-start border-primary border-4 shadow-sm h-100 hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="text-muted mb-2">Total Jenis Tools</p>
                                            <h3 class="fw-bold mb-0 text-primary">{{ $stats['total_tools'] }}</h3>
                                            <small class="text-primary">
                                                <i class="bi bi-bag me-1"></i>
                                                {{ $stats['new_tools_today'] }} tools baru hari ini
                                            </small>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-2 rounded">
                                            <i class="bi bi-tools text-primary fs-4"></i>
                                        </div>
                                    </div>
                                    @if (isset($stats['total_tools_change']))
                                        <div class="mt-2">
                                            <small class="text-success">
                                                <i class="bi bi-arrow-up"></i> {{ $stats['total_tools_change'] }} dari bulan
                                                lalu
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Sedang Dipinjam -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('borrow.index') }}" class="text-decoration-none">
                            <div class="card border-start border-warning border-4 shadow-sm h-100 hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="text-muted mb-2">Peminjaman Berlangsung</p>
                                            <h3 class="fw-bold mb-0 text-warning">{{ $stats['active_borrows_count'] }}</h3>
                                            <small class="text-warning">
                                                <i class="bi bi-bag me-1"></i>
                                                {{ $stats['unreturned_items_count'] }} barang dipinjam
                                            </small>
                                        </div>
                                        <div class="bg-warning bg-opacity-10 p-2 rounded">
                                            <i class="bi bi-box-arrow-in-right text-warning fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Pengembalian Hari Ini -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('borrow.index', ['is_completed' => 1]) }}" class="text-decoration-none">
                            <div class="card border-start border-success border-4 shadow-sm h-100 hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="text-muted mb-2">Pengembalian Hari Ini</p>
                                            <h3 class="fw-bold mb-0 text-success">{{ $stats['today_returns'] }}</h3>
                                            <small class="text-success">
                                                <i class="bi bi-save"></i> {{ $stats['returned_items_count'] }} barang
                                                dikembalikan
                                            </small>
                                        </div>
                                        <div class="bg-success bg-opacity-10 p-2 rounded">
                                            <i class="bi bi-box-arrow-left text-success fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Telat Kembali -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-start border-danger border-4 shadow-sm h-100 hover-shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted mb-2">Tools Rusak</p>
                                        <h3 class="fw-bold mb-0 text-danger">{{ $stats['broken_tools_count'] }}</h3>
                                        <small class="text-danger">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            {{ $stats['broken_tools_today_count'] }} dilaporkan hari ini
                                        </small>
                                    </div>
                                    <div class="bg-danger bg-opacity-10 p-2 rounded">
                                        <i class="bi bi-exclamation-circle text-danger fs-4"></i>
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
                                <div style="height: 90%; min-height: 400px;">
                                    <canvas id="weeklyChart"></canvas>
                                </div>
                                <div class="mt-5 justify-content-center d-flex">
                                    {{-- <table class="table table-sm mt-3 table-bordered mb-5" style="width: 75%">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="table-secondary text-center" style="width: 50px;">
                                                    No</th>
                                                <th scope="col" class="table-secondary ps-3" style="width: 120px;">Hari
                                                </th>
                                                <th scope="col" class="table-secondary text-center" style="width: 80px;">
                                                    Peminjaman</th>
                                                <th scope="col" class="table-secondary text-center" style="width: 80px;">
                                                    Pengembalian</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @for ($i = 6; $i >= 0; $i--)
                                                @php
                                                    $date = now()->subDays($i);
                                                    $borrowCount = $chartData['borrow_data'][6 - $i] ?? 0;
                                                    $returnCount = $chartData['return_data'][6 - $i] ?? 0;
                                                    $isToday = $date->isToday();
                                                @endphp
                                                <tr class="{{ $isToday ? 'table-primary' : '' }}">
                                                    <td class="text-center align-middle text-muted">{{ $no++ }}
                                                    </td>
                                                    <td class="ps-3">
                                                        <div class="fw-semibold {{ $isToday ? 'text-primary' : '' }}">
                                                            {{ $date->translatedFormat('l') }},
                                                        </div>
                                                        <small class="fw-medium {{ $isToday ? 'text-primary' : '' }}">
                                                            {{ $date->format('d/m') }}
                                                        </small>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $borrowCount }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ $returnCount }}
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table> --}}
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
                                    @foreach ($recentActivities as $activity)
                                        <div class="list-group-item border-0 py-3 px-3">
                                            <div class="d-flex">
                                                <div class="shrink-0">
                                                    <i
                                                        class="bi {{ $activity['icon'] }} text-{{ $activity['color'] }} fs-5"></i>
                                                </div>
                                                <div class="grow ms-3">
                                                    <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                                    <p class="text-muted mb-1">
                                                        <strong>{{ $activity['name'] }}</strong>
                                                        {{ $activity['description'] }}
                                                        @if ($activity['job_reference'])
                                                            <strong>{{ $activity['job_reference'] }}</strong>
                                                        @endif
                                                    </p>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($activity['time'])->locale('id')->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if ($recentActivities->isEmpty())
                                        <div class="list-group-item border-0 py-4 text-center text-muted">
                                            <i class="bi bi-inbox fs-1 mb-2 opacity-50"></i>
                                            <p class="mb-0">Belum ada aktivitas</p>
                                        </div>
                                    @endif
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
            const chartData = @json($chartData);

            const ctx = document.getElementById('weeklyChart').getContext('2d');
            const weeklyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Peminjaman',
                        data: chartData.borrowCounts,
                        backgroundColor: '#0d6efd',
                        borderColor: '#0b5ed7',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                    }, {
                        label: 'Pengembalian',
                        data: chartData.returnCounts,
                        backgroundColor: '#198754',
                        borderColor: '#157347',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                    }, {
                        label: 'Tools Rusak',
                        data: chartData.brokenCounts,
                        backgroundColor: '#dc3545',
                        borderColor: '#bb2d3b',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
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
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
