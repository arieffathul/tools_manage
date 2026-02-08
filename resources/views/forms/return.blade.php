<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Tanpa Identitas | Tools Management</title>
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
                            <i class="bi bi-box-arrow-in-left me-2 text-primary"></i>Pengembalian Tanpa Identitas
                        </h1>
                        <p class="text-muted mb-0 small">Form pengembalian tools tanpa data peminjaman</p>
                    </div>
                    <a href="{{ route('borrowReturn.select') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>
                    </a>
                </div>

                <form id="returnForm" action="{{ route('return.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Informasi Pengembalian -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">1. Informasi Pengembalian</h6>
                        </div>
                        <div class="card-body">
                            <!-- Returner -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Dikembalikan oleh *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="engineerSearch"
                                        placeholder="Masukkan nama engineer" autocomplete="off">
                                    <input type="hidden" name="returner_id" id="selectedEngineerId">
                                </div>
                                <div class="list-group mt-2" id="engineerResults"
                                    style="display: none; max-height: 200px; overflow-y: auto;">
                                </div>

                                <!-- Selected Engineer Card -->
                                <div class="border-start border-3 border-success ps-3 py-2 bg-light rounded mt-2"
                                    id="selectedEngineerCard" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1" id="selectedEngineerName"></h6>
                                        </div>
                                        <span class="badge bg-success me-2" id="selectedEngineerShift"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Reference -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Job Reference *</label>
                                <input type="text" name="job_reference" class="form-control"
                                    placeholder="Masukkan job reference" required>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan (Optional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan pengembalian"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Tools yang Dikembalikan -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">2. Tools yang Dikembalikan</h6>
                            <div>
                                <span class="badge bg-primary" id="cartCount">0</span>
                                <button type="button" class="btn btn-sm btn-primary" id="addToolBtn">
                                    <i class=" bi bi-plus me-1"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Empty State -->
                            <div class="text-center text-muted py-5" id="emptyCart">
                                <i class="bi bi-tools display-6 mb-3 opacity-50"></i>
                                <p class="mb-1">Belum ada tool yang ditambahkan</p>
                                <small>Silahkan tambahkan tools yang akan dikembalikan</small>
                            </div>

                            <!-- Cart Items Container -->
                            <div id="cartItems" style="min-height: 100px;"></div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-2">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="resetForm()">
                                <i class="bi bi-x-circle me-1"></i> Reset
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success w-100" id="submitBtn" disabled>
                                <i class="bi bi-check-circle me-1"></i> Simpan Pengembalian
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast -->
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
        document.addEventListener('DOMContentLoaded', function() {
            // ==================== GLOBAL VARIABLES ====================
            let cart = [];
            let selectedEngineer = null;
            const engineers = @json($engineers ?? []);
            const tools = @json($toolsFormatted ?? []);
            let toolCounter = 0;

            // DOM Elements
            const engineerSearch = document.getElementById('engineerSearch');
            const engineerResults = document.getElementById('engineerResults');
            const selectedEngineerCard = document.getElementById('selectedEngineerCard');
            const selectedEngineerName = document.getElementById('selectedEngineerName');
            const selectedEngineerShift = document.getElementById('selectedEngineerShift');
            const selectedEngineerId = document.getElementById('selectedEngineerId');
            const cartItems = document.getElementById('cartItems');
            const emptyCart = document.getElementById('emptyCart');
            const cartCount = document.getElementById('cartCount');
            const addToolBtn = document.getElementById('addToolBtn');
            const submitBtn = document.getElementById('submitBtn');
            const toast = new bootstrap.Toast(document.getElementById('liveToast'));
            const toastMessage = document.getElementById('toastMessage');

            // ==================== UTILITY FUNCTIONS ====================
            function showToast(message, type = 'success') {
                const icon = document.querySelector('#liveToast .toast-header i');
                const header = document.querySelector('#liveToast .toast-header strong');

                if (type === 'success') {
                    icon.className = 'bi bi-check-circle-fill text-success me-2';
                    header.textContent = 'Success';
                } else if (type === 'error') {
                    icon.className = 'bi bi-exclamation-circle-fill text-danger me-2';
                    header.textContent = 'Error';
                } else {
                    icon.className = 'bi bi-info-circle-fill text-info me-2';
                    header.textContent = 'Info';
                }

                toastMessage.textContent = message;
                toast.show();
            }

            function updateSubmitButton() {
                submitBtn.disabled = !(selectedEngineer && cart.length > 0);
            }

            function updateCartCounter() {
                cartCount.textContent = cart.length;
            }

            // ==================== ENGINEER FUNCTIONS ====================
            engineerSearch.addEventListener('input', function(e) {
                const query = e.target.value.trim().toLowerCase();
                engineerResults.innerHTML = '';

                if (query.length < 1) {
                    engineerResults.style.display = 'none';
                    return;
                }

                const filtered = engineers.filter(engineer =>
                    engineer.name.toLowerCase().includes(query)
                ).slice(0, 5);

                if (filtered.length === 0) {
                    engineerResults.innerHTML =
                        `<div class="list-group-item text-muted">Tidak ditemukan</div>`;
                } else {
                    filtered.forEach(engineer => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action text-start';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${engineer.name}</strong><br>
                                    <small class="text-muted">${engineer.email || ''}</small>
                                </div>
                                ${engineer.shift ? `<span class="badge bg-info">${engineer.shift}</span>` : ''}
                            </div>
                        `;
                        item.addEventListener('click', () => selectEngineer(engineer));
                        engineerResults.appendChild(item);
                    });
                }
                engineerResults.style.display = 'block';
            });

            function selectEngineer(engineer) {
                selectedEngineer = engineer;
                selectedEngineerName.textContent = engineer.name;
                selectedEngineerShift.textContent = engineer.shift || '';
                selectedEngineerId.value = engineer.id;
                selectedEngineerCard.style.display = 'block';
                engineerSearch.value = engineer.name;
                engineerResults.style.display = 'none';
                showToast(`Engineer ${engineer.name} dipilih`);
                updateSubmitButton();
            }

            // ==================== TOOL FUNCTIONS ====================
            addToolBtn.addEventListener('click', function() {
                addToolItem();
            });

            function addToolItem(toolId = '', toolName = '', quantity = 1, locator = '') {
                const index = toolCounter++;

                // Hide empty cart
                if (emptyCart.style.display !== 'none') {
                    emptyCart.style.display = 'none';
                }

                const toolItem = document.createElement('div');
                toolItem.className = 'tool-item border-bottom p-3';
                toolItem.id = `tool-item-${index}`;
                toolItem.innerHTML = `
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <label class="form-label small fw-semibold">Tool *</label>
                            <select name="details[${index}][tool_id]"
                                    class="form-select form-select-sm tool-select" required>
                                <option value="">Pilih tool</option>
                                ${tools.map(tool => `
                                                                                    <option value="${tool.id}"
                                                                                            data-locator="${tool.current_locator || tool.locator}"
                                                                                            data-max-quantity="${tool.quantity}"
                                                                                            ${tool.id == toolId ? 'selected' : ''}>
                                                                                        ${tool.name} (Stock: ${tool.current_quantity})
                                                                                    </option>
                                                                                `).join('')}
                            </select>
                        </div>

                        <div class="col-md-2 mb-2 mb-md-0">
                            <label class="form-label small fw-semibold">Quantity *</label>
                            <div class="input-group input-group-sm">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="window.decreaseQuantity(${index})">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" name="details[${index}][quantity]"
                                       class="form-control text-center quantity-input"
                                       value="${quantity}" min="1" required>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="window.increaseQuantity(${index})">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="form-label small fw-semibold">Lokator *</label>
                            <input type="text" name="details[${index}][locator]"
                                   class="form-control form-control-sm locator-input"
                                   value="${locator}" required>
                        </div>

                        <div class="col-md-2 mb-2 mb-md-0">
                            <label class="form-label small fw-semibold">Gambar</label>
                            <input type="file" name="details[${index}][image]"
                                   class="form-control form-control-sm" accept="image/*">
                        </div>

                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-danger"
                                    onclick="window.removeToolItem(${index})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;

                cartItems.appendChild(toolItem);
                cart.push({
                    index: index
                });
                updateCartCounter();
                updateSubmitButton();
                showToast('Tool berhasil ditambahkan');
            }

            // Global functions untuk tool management
            window.decreaseQuantity = function(index) {
                const input = document.querySelector(`#tool-item-${index} .quantity-input`);
                const current = parseInt(input.value);
                if (current > 1) {
                    input.value = current - 1;
                }
            };

            window.increaseQuantity = function(index) {
                const input = document.querySelector(`#tool-item-${index} .quantity-input`);
                const select = document.querySelector(`#tool-item-${index} .tool-select`);
                const selectedOption = select.options[select.selectedIndex];
                const maxQuantity = selectedOption ? parseInt(selectedOption.dataset.maxQuantity) || 999 : 999;

                const current = parseInt(input.value);
                if (current < maxQuantity) {
                    input.value = current + 1;
                } else {
                    showToast(`Jumlah melebihi stock tersedia (${maxQuantity})`, 'error');
                }
            };

            window.removeToolItem = function(index) {
                if (confirm('Hapus tool ini dari daftar?')) {
                    const toolItem = document.getElementById(`tool-item-${index}`);
                    if (toolItem) {
                        toolItem.remove();
                        cart = cart.filter(item => item.index !== index);
                        updateCartCounter();
                        updateSubmitButton();

                        // Show empty cart if no items
                        if (cart.length === 0) {
                            emptyCart.style.display = 'block';
                        }

                        showToast('Tool dihapus');
                    }
                }
            };

            // Auto-fill locator when tool is selected
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('tool-select')) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const locator = selectedOption.dataset.locator;
                    const locatorInput = e.target.closest('.row').querySelector('.locator-input');

                    if (locator && locatorInput) {
                        locatorInput.value = locator;
                    }

                    // Update max quantity
                    const maxQuantity = selectedOption.dataset.maxQuantity;
                    const quantityInput = e.target.closest('.row').querySelector('.quantity-input');
                    if (maxQuantity && quantityInput) {
                        quantityInput.max = maxQuantity;
                        if (parseInt(quantityInput.value) > maxQuantity) {
                            quantityInput.value = maxQuantity;
                        }
                    }
                }
            });

            // Form Submission
            // Form Submission - Diperbaiki
            document.getElementById('returnForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Validation
                if (!selectedEngineer) {
                    showToast('Harap pilih engineer yang mengembalikan', 'error');
                    return;
                }

                if (cart.length === 0) {
                    showToast('Harap tambahkan setidaknya satu tool', 'error');
                    return;
                }

                // Create FormData dengan SEMUA data
                const formData = new FormData(this);

                // Tambahkan semua detail dari cart
                const toolItems = document.querySelectorAll('.tool-item');
                toolItems.forEach((item, index) => {
                    const toolSelect = item.querySelector('.tool-select');
                    const quantityInput = item.querySelector('.quantity-input');
                    const locatorInput = item.querySelector('.locator-input');
                    const imageInput = item.querySelector('input[type="file"]');

                    if (toolSelect && toolSelect.value) {
                        formData.append(`details[${index}][tool_id]`, toolSelect.value);
                    }

                    if (quantityInput && quantityInput.value) {
                        formData.append(`details[${index}][quantity]`, quantityInput.value);
                    }

                    if (locatorInput && locatorInput.value) {
                        formData.append(`details[${index}][locator]`, locatorInput.value);
                    }

                    if (imageInput && imageInput.files[0]) {
                        formData.append(`details[${index}][image]`, imageInput.files[0]);
                    }
                });

                // Show loading
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
                submitBtn.disabled = true;

                // Submit via AJAX
                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showToast(data.message || 'Pengembalian berhasil disimpan!', 'success');
                            setTimeout(() => {
                                window.location.href = data.redirect ||
                                    '{{ route('forms.complete') }}';
                            }, 1500);
                        } else {
                            showToast(data.message || 'Terjadi kesalahan', 'error');
                            submitBtn.innerHTML =
                                '<i class="bi bi-check-circle me-1"></i> Simpan Pengembalian';
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan saat menyimpan data', 'error');
                        submitBtn.innerHTML =
                            '<i class="bi bi-check-circle me-1"></i> Simpan Pengembalian';
                        submitBtn.disabled = false;
                    });
            });

            // Reset Form
            window.resetForm = function() {
                if (confirm('Reset form? Semua data akan hilang.')) {
                    selectedEngineer = null;
                    cart = [];

                    // Reset UI
                    selectedEngineerCard.style.display = 'none';
                    engineerSearch.value = '';
                    selectedEngineerId.value = '';

                    // Clear cart items
                    document.querySelectorAll('.tool-item').forEach(item => item.remove());
                    cartItems.innerHTML = '';
                    emptyCart.style.display = 'block';

                    // Reset form fields
                    document.querySelector('input[name="job_reference"]').value = '';
                    document.querySelector('textarea[name="notes"]').value = '';

                    // Reset counters
                    toolCounter = 0;
                    updateCartCounter();
                    updateSubmitButton();

                    showToast('Form berhasil direset');
                }
            };

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!engineerSearch.contains(e.target) && !engineerResults.contains(e.target)) {
                    engineerResults.style.display = 'none';
                }
            });

            // Initialize
            updateSubmitButton();
        });
    </script>
</body>

</html>
