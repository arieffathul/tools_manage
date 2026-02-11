<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Form | Tools Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h4 fw-bold mb-1">
                            <i class="bi bi-tools me-2 text-primary"></i>Peminjaman Tools
                        </h1>
                        <p class="text-muted mb-0 small">Harap isi sebelum meminjam tools dan spare part</p>
                    </div>
                </div>

                <form id="borrowForm" action="{{ route('borrow.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Engineer Selection -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">Pilih Engineer dan Pekerjaan</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="form-label fw-semibold">Pilih Engineer *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="engineerSearch"
                                        placeholder="Masukkan Nama" autocomplete="off">

                                </div>

                                <!-- Search Results -->
                                <div class="list-group mt-2" id="engineerResults"
                                    style="display: none; max-height: 200px; overflow-y: auto;">
                                </div>
                            </div>

                            <!-- Selected Engineer -->
                            <div class="border-start border-3 border-success ps-3 py-2 bg-light rounded"
                                id="selectedEngineerCard" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1" id="selectedEngineerName"></h6>
                                        <small class="text-muted" id="selectedEngineerDetails"></small>
                                    </div>
                                    <span class="badge bg-success me-2" id="selectedEngineerShift"></span>
                                </div>
                            </div>
                            <input type="hidden" id="selectedEngineerId">


                            {{-- Job Reference --}}
                            <div class="mb-2">
                                <label class="form-label fw-semibold">Job Reference *</label>
                                <input type="text" class="form-control" id="jobReference"
                                    placeholder="input deskripsi pekerjaan" required>
                            </div>
                        </div>

                    </div>

                    <!-- Tool Selection -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">Pilih Tools</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="form-label fw-semibold">Cari Tool *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="toolSearch"
                                        placeholder="Input Nama Tool" autocomplete="off">

                                </div>

                                <!-- Search Results -->
                                <div class="list-group mt-2" id="toolResults"
                                    style="display: none; max-height: 200px; overflow-y: auto;">
                                    <!-- Results will appear here -->
                                </div>
                            </div>

                            <!-- Selected Tool & Quantity -->
                            <div class="row g-2 mt-3" id="selectedToolSection" style="display: none;">
                                <div class="col-md-6">
                                    <div class="border rounded p-3 bg-light">
                                        <div class="d-flex align-items-start mb-2">
                                            <div class="shrink-0 me-3">
                                                <div id="selectedToolImageContainer">
                                                    <!-- Image will be inserted here by JavaScript -->
                                                </div>
                                            </div>
                                            <div class="grow">
                                                <h6 class="mb-1" id="selectedToolName"></h6>
                                                <p class="mb-1 small">
                                                    <span class="text-muted">Code:</span>
                                                    <span id="selectedToolCode" class="fw-semibold"></span>
                                                </p>
                                                <p class="mb-1 small" id="selectedToolDescriptionContainer">
                                                    <!-- Description will be inserted here by JavaScript -->
                                                </p>
                                                <p class="mb-1 small">
                                                    <span class="text-muted">Jumlah Tersedia:</span>
                                                    <span id="selectedToolAvailable" class="badge bg-info"></span>
                                                </p>
                                                <p class="mb-0 small">
                                                    <span class="text-muted">Locator:</span>
                                                    <span id="selectedToolLocator"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jumlah dipinjam</label>
                                    <div class="input-group mb-2">
                                        <button class="btn btn-outline-secondary" type="button" id="decreaseQty">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control text-center" id="toolQuantity"
                                            value="1" min="1" max="1">
                                        <button class="btn btn-outline-secondary" type="button" id="increaseQty">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-success w-100" id="addToCart">
                                        <i class="bi bi-cart-plus"></i> Pinjam
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Section -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">Daftar Tools Dipinjam</h6>
                            <div>
                                <span class="badge bg-primary" id="cartCount">0</span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="clearCart"
                                    disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="cartItems" style="min-height: 100px;">
                                <div class="text-center text-muted py-5" id="emptyCart">
                                    <i class="bi bi-bag-x display-6 mb-3 opacity-50"></i>
                                    <p class="mb-1">Keranjang kosong</p>
                                    <small>Silahkan tambahkan tools yang ingin dipinjam</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">Informasi Tambahan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Upload Foto (semua tool) *</label>
                                    <input type="file" class="form-control" id="borrowPhoto" accept="image/*"
                                        required>
                                    <!-- Preview Container -->
                                    <div class="mt-3" id="photoPreviewContainer" style="display: none;">
                                        <div class="border rounded p-2 bg-light">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">Preview Foto:</small>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    id="removePhotoBtn">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>
                                            <div class="text-center">
                                                <img id="photoPreview" class="img-fluid rounded"
                                                    style="max-height: 200px; object-fit: contain;"
                                                    alt="Preview foto yang akan diupload">
                                            </div>
                                            <div class="text-center mt-2">
                                                <small id="photoInfo" class="text-muted"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Note (Optional)</label>
                                    <textarea class="form-control" id="notes" rows="2" placeholder="Tulis catatan terkait peminjaman tools"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-2">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-secondary w-100" onclick="resetForm()">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success w-100" id="submitBtn" disabled>
                                <i class="bi bi-check-circle"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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

</body>

</html>
