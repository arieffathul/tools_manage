<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Select | Tools Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header -->
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                    <div class="mb-2 mb-md-0">
                        <h1 class="h4 fw-bold mb-1">
                            <i class="bi bi-tools me-2 text-primary"></i>Pengembalian Tools
                        </h1>
                        <p class="text-muted mb-0 small">Pilih data peminjaman yang akan dikembalikan</p>
                    </div>
                    <div>
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-person-x me-1"></i> Tanpa Identitas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrow List -->
        <div class="row g-3">
            @forelse ($borrow as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <!-- Card Header -->
                            <div class="d-flex align-items-start mb-3">
                                <!-- Image -->
                                <div class="me-3 shrink-0">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}"
                                            alt="peminjaman {{ $item->engineer->name ?? 'No Engineer' }}"
                                            class="rounded" style="width: 70px; height: 70px; object-fit: cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                            style="width: 70px; height: 70px;">
                                            <i class="bi bi-tools text-muted fs-5"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="grow">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold mb-1 text-truncate" style="max-width: 150px;">
                                                {{ $item->engineer->name ?? '-' }}
                                            </h6>
                                            <span class="badge bg-light text-muted">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $item->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Job Reference -->
                                    <div class="mt-2">
                                        <small class="text-muted">Job Reference:</small>
                                        <p class="mb-0 fw-medium text-truncate" style="max-width: 200px;">
                                            {{ $item->job_reference ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tools List -->
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">Tools yang dipinjam:</small>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($item->borrowDetails->take(3) as $detail)
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ $detail->tool->name ?? 'Unknown' }}
                                            <span class="badge bg-primary rounded-pill ms-1">
                                                {{ $detail->quantity }}
                                            </span>
                                        </span>
                                    @endforeach

                                    @if ($item->borrowDetails->count() > 3)
                                        <span class="badge bg-light text-muted">
                                            +{{ $item->borrowDetails->count() - 3 }} lagi
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-auto">
                                <a href="#"
                                    class="btn btn-success w-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Pilih untuk Pengembalian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-center" style="min-height: 50vh">
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">Tidak ada data peminjaman</h4>
                            <p class="text-muted mb-4">
                                Semua peminjaman telah dikembalikan<br>atau tidak ada data peminjaman aktif.
                            </p>
                            <a href="{{ url()->previous() }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination (if needed) -->
        {{-- @if ($borrow->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <nav>
                        {{ $borrow->links() }}
                    </nav>
                </div>
            </div>
        @endif --}}
    </div>

    <!-- Toast for notifications -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show toast if there's a success message
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                const toast = new bootstrap.Toast(document.getElementById('liveToast'));
                const toastMessage = document.getElementById('toastMessage');

                if (urlParams.get('success') === 'returned') {
                    toastMessage.textContent = 'Pengembalian berhasil dicatat!';
                }

                toast.show();
            }
        });
    </script>
</body>

</html>
