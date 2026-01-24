@extends('layouts.app')
@php $currentPage = 'dashboard'; @endphp

@section('title', 'Dashboard Admin')
@section('content')
    <main class="app-main">
        <!-- Header -->
        <div class="app-content-header py-4 mb-4 bg-white border-bottom shadow-sm animate-fade-in">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1 text-primary fw-semibold animate-fade-in-up">
                            <i class="bi bi-speedometer2 me-2 text-secondary"></i>Dashboard Admin
                        </h3>
                        <p class="text-muted mb-0 animate-slide-up">
                            Hai <strong>{{ Auth::user()->name }}</strong>, Pantau aktivitas peminjaman tools STO03</p>
                    </div>
                    <div class="text-end animate-zoom-in">
                        <span
                            class="d-inline-flex align-items-center gap-2 px-3 py-2 bg-white rounded shadow-sm text-secondary small">
                            <i class="bi bi-calendar-event"></i>
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </span>

                    </div>
                </div>
            </div>
        </div>

        <div class="app-content mb-4">
            <div class="container-fluid">
                <div class="row g-4">
                </div>
            </div>
        </div>

        <!-- Jadwal & Statistik -->
        <div class="app-content mt-4">
            <div class="container-fluid">
                <div class="row g-4">
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
    <style>
        .animate-fade-in {
            animation: fadeIn 1s ease forwards
        }

        .animate-fade-in-up {
            animation: fadeInUp 1.2s ease forwards
        }

        .animate-slide-up {
            animation: slideUp 1.2s ease forwards
        }

        .animate-zoom-in {
            animation: zoomIn 0.9s ease forwards
        }

        .transition-box {
            transition: all .3s ease-in-out;
            padding: 1.25rem
        }

        .transition-box:hover {
            transform: scale(1.015);
            background-color: #f1f5ff !important;
            box-shadow: 0 6px 12px rgba(0, 0, 0, .06);
            border-left-color: #0d6efd
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(.8);
                opacity: 0
            }

            to {
                transform: scale(1);
                opacity: 1
            }
        }

        .activity-item {
            animation: fadeInUp .7s ease both
        }

        .activity-item:nth-child(1) {
            animation-delay: .1s
        }

        .activity-item:nth-child(2) {
            animation-delay: .2s
        }

        .activity-item:nth-child(3) {
            animation-delay: .3s
        }

        .activity-item:nth-child(4) {
            animation-delay: .4s
        }

        .activity-item:nth-child(5) {
            animation-delay: .5s
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {


            // Efek hover zoom untuk semua card
            document.querySelectorAll('.dashboard-card').forEach(card => {
                card.addEventListener('mouseenter', () => card.style.transform = 'scale(1.01)');
                card.addEventListener('mouseleave', () => card.style.transform = 'scale(1)');
                card.style.transition = 'transform 0.3s ease';
                card.style.borderRadius = '1rem';
            });
        });
    </script>
@endpush
