<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Broken Tools | Tools Management</title>
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
                            <i class="bi bi-tools me-2 text-warning"></i>Laporan Alat Rusak
                        </h1>
                        <p class="text-muted mb-0 small">Pilih data laporan alat rusak untuk diperbarui</p>
                    </div>
                </div>
                <div class="mb-2 d-flex justify-content-end">
                    <a href="{{ route('broken.form') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Buat Laporan Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Broken Tools List -->
        <div class="row g-3">
            @forelse ($brokenTools as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <!-- Card Header -->
                            <div class="d-flex align-items-start mb-3">
                                <!-- Image -->
                                <div class="me-3 shrink-0">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="Laporan alat rusak"
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
                                                {{ $item->tool->name ?? 'Alat Tidak Dikenal' }}
                                            </h6>
                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    class="badge @if ($item->status == 'pending') bg-warning
                                                    @elseif($item->status == 'in_progress') bg-info
                                                    @elseif($item->status == 'waiting_parts') bg-danger
                                                    @else bg-secondary @endif">
                                                    {{ str_replace('_', ' ', ucfirst($item->status)) }}
                                                </span>
                                                <span class="badge bg-light text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $item->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="mt-2">
                                        <small class="text-muted">Jumlah Rusak:</small>
                                        <p class="mb-0 fw-medium">
                                            {{ $item->quantity }} unit
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="mb-3">
                                <!-- Reported By -->
                                <div class="mb-2">
                                    <small class="text-muted">Dilaporkan oleh:</small>
                                    <p class="mb-0 fw-medium">
                                        {{ $item->reporter->name ?? '-' }}
                                    </p>
                                </div>

                                <!-- Handled By -->
                                @if ($item->handled_by)
                                    <div class="mb-2">
                                        <small class="text-muted">Ditangani oleh:</small>
                                        <p class="mb-0 fw-medium">
                                            {{ $item->handler->name ?? '-' }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Issue -->
                                @if ($item->issue)
                                    <div class="mb-2">
                                        <small class="text-muted">Masalah:</small>
                                        <p class="mb-0 fw-medium text-truncate" style="max-width: 200px;">
                                            {{ $item->issue }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Locator -->
                                @if ($item->locator)
                                    <div class="mb-2">
                                        <small class="text-muted">Lokasi:</small>
                                        <p class="mb-0 fw-medium">
                                            <i class="bi bi-geo-alt me-1"></i> {{ $item->locator }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Last Used -->
                                @if ($item->last_used)
                                    <div class="mb-2">
                                        <small class="text-muted">Terakhir Digunakan:</small>
                                        <p class="mb-0 fw-medium">
                                            {{ $item->last_used }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="mt-auto">
                                <a href="{{ route('broken.edit', $item->id) }}"
                                    class="btn @if ($item->status == 'pending') btn-warning
                                           @elseif($item->status == 'in_progress') btn-info
                                           @elseif($item->status == 'waiting_parts') btn-danger
                                           @else btn-secondary @endif w-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-pencil-square me-2"></i>
                                    Perbarui Laporan
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
                                <i class="bi bi-check-circle display-1 text-success"></i>
                            </div>
                            <h4 class="text-muted mb-3">Tidak ada laporan alat rusak</h4>
                            <p class="text-muted mb-4">
                                Semua laporan alat rusak telah selesai diperbaiki<br>atau tidak ada laporan aktif.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination (if needed) -->
        {{-- @if ($brokenTools->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <nav>
                        {{ $brokenTools->links() }}
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

                if (urlParams.get('success') === 'updated') {
                    toastMessage.textContent = 'Laporan berhasil diperbarui!';
                } else if (urlParams.get('success') === 'created') {
                    toastMessage.textContent = 'Laporan berhasil dibuat!';
                }

                toast.show();
            }
        });
    </script>
</body>

</html>
