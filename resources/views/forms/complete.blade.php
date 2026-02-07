<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete | Tools Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success-card {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .check-icon {
            animation: checkmarkDraw 0.5s ease-in-out 0.2s both;
        }

        @keyframes checkmarkDraw {
            0% {
                stroke-dashoffset: 100;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <!-- Header (sama seperti form) -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h4 fw-bold mb-1">
                            <i class="bi bi-tools me-2 text-primary"></i>Peminjaman Tools
                        </h1>
                        <p class="text-muted mb-0 small">Sistem Management Tools</p>
                    </div>
                </div>

                <!-- Success Message Card -->
                <div class="card shadow-sm border-0 success-card">
                    <div class="card-body text-center p-5">
                        <!-- SVG Checkmark -->
                        <div class="mb-4">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="#198754" />
                                <path d="M7 12L10.5 15.5L17 9" stroke="white" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round" class="check-icon"
                                    style="stroke-dasharray: 100; stroke-dashoffset: 100;" />
                            </svg>
                        </div>

                        <!-- Title & Message -->
                        <h3 class="fw-bold text-success mb-3">Sukses!</h3>
                        <p class="text-muted mb-4">
                            Form peminjaman tools telah berhasil disimpan.
                        </p>

                        <!-- Session Messages -->
                        @if (session('success'))
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-4">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('borrow.form') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Buat Peminjaman Baru
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate the checkmark
            const checkPath = document.querySelector('.check-icon');
            if (checkPath) {
                // Reset animation
                checkPath.style.strokeDashoffset = '100';
                setTimeout(() => {
                    checkPath.style.transition = 'stroke-dashoffset 0.5s ease-in-out';
                    checkPath.style.strokeDashoffset = '0';
                }, 200);
            }
        });
    </script>
</body>

</html>
