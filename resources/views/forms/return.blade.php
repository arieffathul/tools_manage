<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Tools | Tools Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .search-results {
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background: white;
            z-index: 1000;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: background-color 0.15s;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .tool-image-small {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 4px;
        }

        .tool-info {
            font-size: 0.85rem;
        }

        .selected-tool-card {
            border-left: 4px solid #198754;
            background-color: #f8fff9;
        }

        .borrow-summary-card {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
        }

        .tool-status-badge {
            font-size: 0.7rem;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h4 fw-bold mb-1">
                            <i class="bi bi-tools me-2 text-primary"></i>
                            @if ($borrow)
                                Pengembalian dengan Identitas
                            @else
                                Pengembalian Tanpa Identitas
                            @endif
                        </h1>
                        <p class="text-muted mb-0 small">
                            @if ($borrow)
                                Form pengembalian tools berdasarkan data peminjaman
                            @else
                                Form pengembalian tools tanpa data peminjaman
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('borrowReturn.select') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>
                    </a>
                </div>

                <form id="returnForm" action="{{ route('return.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Hidden borrow_id for form submission -->
                    @if ($borrow)
                        <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
                    @endif

                    <!-- Informasi Pengembalian -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">1. Informasi Pengembalian</h6>
                        </div>
                        <div class="card-body">
                            @if ($borrow)
                                {{-- borrower --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Dipinjam oleh *</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" placeholder="Masukkan nama engineer"
                                            disabled
                                            @if ($borrow && $borrow->engineer) value="{{ $borrow->engineer->name }}" @endif>
                                    </div>
                                </div>
                            @endif
                            <!-- Returner -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Dikembalikan oleh *</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="engineerSearch"
                                        placeholder="Masukkan nama engineer" autocomplete="off"
                                        @if ($borrow && $borrow->engineer) value="{{ $borrow->engineer->name }}" @endif>
                                    <input type="hidden" name="returner_id" id="selectedEngineerId"
                                        @if ($borrow && $borrow->engineer) value="{{ $borrow->engineer->id }}" @endif>
                                    <div class="search-results position-absolute w-100 mt-1" id="engineerResults"
                                        style="display: none;"></div>
                                </div>

                                <!-- Selected Engineer Card -->
                                <div class="selected-tool-card rounded p-3 mt-2" id="selectedEngineerCard"
                                    @if ($borrow && $borrow->engineer) style="display: block;"
                                    @else
                                        style="display: none;" @endif>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1" id="selectedEngineerName">
                                                @if ($borrow && $borrow->engineer)
                                                    {{ $borrow->engineer->name }}
                                                @endif
                                            </h6>
                                        </div>
                                        <div>
                                            <span class="badge bg-success me-2" id="selectedEngineerShift">
                                                @if ($borrow && $borrow->engineer)
                                                    {{ $borrow->engineer->shift ?? '' }}
                                                @endif
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="clearSelectedEngineer()">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Reference -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Job Reference *</label>
                                <input type="text" name="job_reference" class="form-control"
                                    placeholder="Masukkan job reference"
                                    @if ($borrow && $borrow->job_reference) value="{{ $borrow->job_reference }}" @endif
                                    required>
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
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($borrow && $borrowDetails->count() > 0)
                                <!-- Pre-loaded tools from borrow -->
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-3">Tools yang Dipinjam:</h6>
                                    <div id="preloadedTools">
                                        @foreach ($borrowDetails as $detail)
                                            <div class="border rounded p-3 mb-2 preloaded-tool-item"
                                                data-tool-id="{{ $detail['tool_id'] }}">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="d-flex">
                                                        <div class="shrink-0 me-3">
                                                            <div
                                                                class="tool-image-small bg-light rounded d-flex align-items-center justify-content-center">
                                                                <i class="bi bi-tools text-muted"></i>
                                                            </div>
                                                        </div>
                                                        <div class="grow">
                                                            <h6 class="mb-1">{{ $detail['tool_name'] }}</h6>
                                                            <div class="text-muted small">
                                                                <i class="bi bi-box"></i> Dipinjam:
                                                                {{ $detail['borrowed_quantity'] }} unit |
                                                                <i class="bi bi-geo-alt"></i> Lokasi:
                                                                {{ $detail['locator'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-sm btn-success add-preloaded-tool"
                                                        data-tool-id="{{ $detail['tool_id'] }}"
                                                        data-tool-name="{{ $detail['tool_name'] }}"
                                                        data-max-quantity="{{ $detail['max_quantity'] }}"
                                                        data-locator="{{ $detail['locator'] }}">
                                                        <i class="bi bi-plus"></i> Tambah
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h6 class="fw-semibold mb-3">Atau tambah tool manual:</h6>
                            @endif

                            <!-- Search Tool -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    @if ($borrow)
                                        Cari Tool Tambahan
                                    @else
                                        Cari Tool
                                    @endif
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="toolSearch"
                                        placeholder="Masukkan nama atau kode tool" autocomplete="off">
                                    <div class="search-results position-absolute w-100 mt-1" id="toolResults"
                                        style="display: none;"></div>
                                </div>
                            </div>

                            <!-- Selected Tool Card (Temporary) -->
                            <div class="selected-tool-card rounded p-3 mb-3" id="selectedToolCard"
                                style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h6 class="mb-1" id="selectedToolName"></h6>
                                        <div class="tool-info text-muted">
                                            <small>
                                                <i class="bi bi-tag"></i> Kode: <span id="selectedToolCode"></span> |
                                                <i class="bi bi-box"></i> Stock: <span id="selectedToolStock"></span>
                                                |
                                                <i class="bi bi-geo-alt"></i> Lokasi: <span
                                                    id="selectedToolLocator"></span>
                                            </small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="clearSelectedTool()">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>

                                <!-- Tool Image -->
                                <div class="row mb-3" id="selectedToolImageRow" style="display: none;">
                                    <div class="col-12">
                                        <div id="selectedToolImageContainer"></div>
                                    </div>
                                </div>

                                <!-- Quantity Input -->
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Jumlah Dikembalikan *</label>
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="decreaseToolQuantity()">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" class="form-control text-center" id="toolQuantity"
                                                value="1" min="1">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="increaseToolQuantity()">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold">Lokator *</label>
                                        <input type="text" class="form-control form-control-sm" id="toolLocator"
                                            placeholder="Lokasi penyimpanan" required>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-success w-100" id="addToCartBtn">
                                            <i class="bi bi-box-arrow-in-left me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Items Container -->
                            <div id="cartContainer" style="min-height: 100px;">
                                <div class="text-center text-muted py-5" id="emptyCart">
                                    <i class="bi bi-save display-6 mb-3 opacity-50"></i>
                                    <p class="mb-1">Belum ada tool yang ditambahkan</p>
                                    <small>Silahkan tambahkan tools yang akan dikembalikan</small>
                                </div>
                            </div>
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
                            <button type="submit" class="btn btn-success w-100" id="submitBtn">
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
            let selectedTool = null;
            const engineers = @json($engineers ?? []);
            const tools = @json($toolsFormatted ?? []);
            let toolCounter = 0;
            const hasBorrow = @json($borrow ? true : false);
            const borrowDetails = @json($borrowDetails ?? []);

            // DOM Elements
            const engineerSearch = document.getElementById('engineerSearch');
            const engineerResults = document.getElementById('engineerResults');
            const selectedEngineerCard = document.getElementById('selectedEngineerCard');
            const selectedEngineerName = document.getElementById('selectedEngineerName');
            const selectedEngineerShift = document.getElementById('selectedEngineerShift');
            const selectedEngineerId = document.getElementById('selectedEngineerId');

            const toolSearch = document.getElementById('toolSearch');
            const toolResults = document.getElementById('toolResults');
            const selectedToolCard = document.getElementById('selectedToolCard');
            const selectedToolName = document.getElementById('selectedToolName');
            const selectedToolCode = document.getElementById('selectedToolCode');
            const selectedToolStock = document.getElementById('selectedToolStock');
            const selectedToolLocator = document.getElementById('selectedToolLocator');
            const selectedToolImageContainer = document.getElementById('selectedToolImageContainer');
            const selectedToolImageRow = document.getElementById('selectedToolImageRow');
            const toolQuantity = document.getElementById('toolQuantity');
            const toolLocator = document.getElementById('toolLocator');
            const addToCartBtn = document.getElementById('addToCartBtn');

            const cartContainer = document.getElementById('cartContainer');
            const emptyCart = document.getElementById('emptyCart');
            const cartCount = document.getElementById('cartCount');
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
                const hasValidEngineer =
                    selectedEngineer !== null ||
                    selectedEngineerId.value !== '';

                // submitBtn.disabled = !(hasValidEngineer && cart.length > 0);
            }



            function updateCartCounter() {
                const count = cart.length;
                cartCount.textContent = count;
                console.log('Cart updated. Count:', count, 'Items:', cart); // Debug
            }

            // ==================== INITIALIZE FOR BORROW ====================
            if (hasBorrow) {
                const engineerId = selectedEngineerId.value;
                if (engineerId) {
                    const engineer = engineers.find(e => e.id == engineerId);
                    if (engineer) {
                        selectedEngineer = engineer;
                        selectedEngineerName.textContent = engineer.name;
                        selectedEngineerShift.textContent = engineer.shift || '';
                        selectedEngineerCard.style.display = 'block';
                    }
                }

                document.querySelectorAll('.add-preloaded-tool').forEach(button => {
                    button.addEventListener('click', function() {
                        const toolId = this.dataset.toolId;
                        const toolName = this.dataset.toolName;
                        const maxQuantity = parseInt(this.dataset.maxQuantity);
                        const locator = this.dataset.locator;

                        if (cart.some(item => item.tool_id == toolId)) {
                            showToast('Tool ini sudah ditambahkan', 'error');
                            return;
                        }

                        const tool = tools.find(t => t.id == toolId);

                        const cartItem = {
                            id: Date.now() + toolCounter++,
                            tool_id: toolId,
                            name: toolName,
                            code: tool?.code || '-',
                            image: tool?.image || null,
                            description: tool?.description || null,
                            quantity: maxQuantity,
                            locator: locator,
                            max_quantity: maxQuantity
                        };

                        cart.push(cartItem);
                        addCartItem(cartItem);

                        this.innerHTML = '<i class="bi bi-check"></i> Ditambahkan';
                        this.disabled = true;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-secondary');

                        showToast(`${toolName} ditambahkan ke daftar`);
                        updateCartCounter();
                        updateSubmitButton();
                    });
                });
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
                        `<div class="search-result-item text-muted">Tidak ditemukan</div>`;
                } else {
                    filtered.forEach(engineer => {
                        const item = document.createElement('div');
                        item.className = 'search-result-item';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${engineer.name}</strong>
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
                console.log('Selecting engineer:', engineer);

                selectedEngineer = engineer; // <-- Ini harusnya mengatur

                // Update card dengan innerHTML untuk memastikan element ada
                selectedEngineerCard.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">${engineer.name}</h6>
            </div>
            <div>
                <span class="badge bg-success me-2">${engineer.shift || ''}</span>
                <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="clearSelectedEngineer()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    `;

                selectedEngineerCard.style.display = 'block';
                selectedEngineerId.value = engineer.id;
                engineerSearch.value = engineer.name;

                if (engineerResults) {
                    engineerResults.style.display = 'none';
                }

                showToast(`Engineer ${engineer.name} dipilih`);
                updateSubmitButton();
            }

            function clearSelectedEngineer() {
                selectedEngineer = null;
                selectedEngineerCard.style.display = 'none';
                engineerSearch.value = '';
                selectedEngineerId.value = '';
                showToast('Engineer dihapus');
                updateSubmitButton();
            }

            // ==================== TOOL FUNCTIONS ====================
            toolSearch.addEventListener('input', function(e) {
                const query = e.target.value.trim().toLowerCase();
                toolResults.innerHTML = '';

                if (query.length < 1) {
                    toolResults.style.display = 'none';
                    return;
                }

                const filtered = tools.filter(tool => {
                    const name = (tool.name || '').toLowerCase();
                    const code = (tool.code || '').toLowerCase();
                    const description = (tool.description || '').toLowerCase();
                    return name.includes(query) || code.includes(query) || description.includes(
                        query);
                }).slice(0, 5);

                if (filtered.length === 0) {
                    toolResults.innerHTML =
                        `<div class="search-result-item text-muted">Tidak ada tool ditemukan</div>`;
                } else {
                    filtered.forEach(tool => {
                        const item = document.createElement('div');
                        item.className = 'search-result-item';
                        item.innerHTML = `
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    ${tool.image ?
                                        `<img src="${tool.image}" alt="${tool.name}" class="tool-image-small">` :
                                        `<div class="tool-image-small bg-light d-flex align-items-center justify-content-center">
                                                                            <i class="bi bi-tools text-muted"></i>
                                                                        </div>`
                                    }
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong class="mb-1">${tool.name || 'No Name'}</strong>
                                            <div class="text-muted small">
                                                <i class="bi bi-tag"></i> ${tool.code || '-'} |
                                                <i class="bi bi-box"></i> Stock: ${tool.current_quantity} |
                                                <i class="bi bi-geo-alt"></i> ${tool.current_locator || 'Unknown'}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        item.addEventListener('click', () => selectTool(tool));
                        toolResults.appendChild(item);
                    });
                }
                toolResults.style.display = 'block';
            });

            function selectTool(tool) {
                if (cart.some(item => item.tool_id === tool.id)) {
                    showToast('Tool ini sudah ditambahkan', 'error');
                    return;
                }

                selectedTool = tool;
                selectedToolName.textContent = tool.name || 'No Name';
                selectedToolCode.textContent = tool.code || '-';
                selectedToolStock.textContent = tool.current_quantity;
                selectedToolLocator.textContent = tool.current_locator || 'Unknown';
                toolLocator.value = tool.current_locator || tool.locator || '';

                const maxQty = parseInt(tool.quantity) || 1;
                toolQuantity.max = maxQty;
                toolQuantity.value = Math.min(1, maxQty);

                if (tool.image) {
                    selectedToolImageContainer.innerHTML = `
                        <img src="${tool.image}" alt="${tool.name}" class="img-fluid rounded" style="max-height: 150px;">
                    `;
                    selectedToolImageRow.style.display = 'block';
                } else {
                    selectedToolImageRow.style.display = 'none';
                }

                selectedToolCard.style.display = 'block';
                toolSearch.value = tool.name || '';
                toolResults.style.display = 'none';

                showToast(`${tool.name || 'Tool'} dipilih`);
            }

            function clearSelectedTool() {
                selectedTool = null;
                selectedToolCard.style.display = 'none';
                toolSearch.value = '';
                toolResults.style.display = 'none';
                // showToast('Tool ditambahkan ke daftar');
            }

            function decreaseToolQuantity() {
                const current = parseInt(toolQuantity.value);
                if (current > 1) toolQuantity.value = current - 1;
            }

            function increaseToolQuantity() {
                const current = parseInt(toolQuantity.value);
                const max = parseInt(toolQuantity.max);
                if (current < max) {
                    toolQuantity.value = current + 1;
                } else {
                    showToast(`Jumlah melebihi stock tersedia (${max})`, 'error');
                }
            }

            // Add tool to cart
            addToCartBtn.addEventListener('click', function() {
                if (!selectedTool) {
                    showToast('Harap pilih tool terlebih dahulu', 'error');
                    return;
                }

                if (!toolLocator.value.trim()) {
                    showToast('Harap isi lokator', 'error');
                    toolLocator.focus();
                    return;
                }

                const cartItem = {
                    id: Date.now() + toolCounter++,
                    tool_id: selectedTool.id,
                    name: selectedTool.name,
                    code: selectedTool.code || '-',
                    image: selectedTool.image || null,
                    description: selectedTool.description || null,
                    quantity: parseInt(toolQuantity.value),
                    locator: toolLocator.value.trim(),
                    max_quantity: parseInt(toolQuantity.max)
                };

                cart.push(cartItem);
                addCartItem(cartItem);
                clearSelectedTool();

                showToast(`${selectedTool.name} ditambahkan ke daftar`);
                updateCartCounter();
                updateSubmitButton();
            });

            function addCartItem(item) {
                if (emptyCart.style.display !== 'none') {
                    emptyCart.style.display = 'none';
                }

                const cartIndex = cart.findIndex(c => c.id === item.id);
                const cartItem = document.createElement('div');
                cartItem.className = 'border rounded p-3 mb-2';
                cartItem.id = `cart-item-${item.id}`;
                cartItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                ${item.image ?
                                    `<img src="${item.image}" alt="${item.name}" class="tool-image-small rounded">` :
                                    `<div class="tool-image-small bg-light rounded d-flex align-items-center justify-content-center">
                                                                        <i class="bi bi-tools text-muted"></i>
                                                                    </div>`
                                }
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${item.name}</h6>
                                <div class="text-muted small">
                                    <i class="bi bi-tag"></i> ${item.code} |
                                    <i class="bi bi-geo-alt"></i> ${item.locator}
                                </div>
                                ${item.description ? `<small class="text-muted">${item.description}</small>` : ''}
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCartItem(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="input-group input-group-sm" style="width: 140px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="updateCartQuantity(${item.id}, -1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control text-center quantity-input"
                                   value="${item.quantity}" min="1" max="${item.max_quantity}"
                                   onchange="updateCartQuantity(${item.id}, 0, this.value)">
                            <button type="button" class="btn btn-outline-secondary" onclick="updateCartQuantity(${item.id}, 1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <input type="file" class="form-control form-control-sm" style="width: 500px;"
                               name="details[${cartIndex}][image]" accept="image/*" required>
                    </div>
                    <input type="hidden" name="details[${cartIndex}][tool_id]" value="${item.tool_id}">
                    <input type="hidden" name="details[${cartIndex}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="details[${cartIndex}][locator]" value="${item.locator}">
                `;

                cartContainer.appendChild(cartItem);
                updateCartCounter();

            }

            // Global cart functions
            window.updateCartQuantity = function(itemId, change, newValue = null) {
                const index = cart.findIndex(item => item.id === itemId);
                if (index === -1) return;

                let newQty = newValue !== null ? parseInt(newValue) : cart[index].quantity + change;
                if (newQty < 1) newQty = 1;
                if (newQty > cart[index].max_quantity) {
                    showToast(`Jumlah melebihi batas (${cart[index].max_quantity})`, 'error');
                    newQty = cart[index].max_quantity;
                }

                cart[index].quantity = newQty;
                const input = document.querySelector(`#cart-item-${itemId} .quantity-input`);
                const hiddenInput = document.querySelector(`#cart-item-${itemId} input[name*="[quantity]"]`);
                if (input) input.value = newQty;
                if (hiddenInput) hiddenInput.value = newQty;
            };

            window.removeCartItem = function(itemId) {
                if (confirm('Hapus tool ini dari daftar?')) {
                    const index = cart.findIndex(item => item.id === itemId);
                    if (index > -1) {
                        if (hasBorrow) {
                            const toolId = cart[index].tool_id;
                            const addButton = document.querySelector(
                                `.add-preloaded-tool[data-tool-id="${toolId}"]`);
                            if (addButton) {
                                addButton.innerHTML = '<i class="bi bi-plus"></i> Tambah';
                                addButton.disabled = false;
                                addButton.classList.remove('btn-secondary');
                                addButton.classList.add('btn-success');
                            }
                        }

                        cart.splice(index, 1);
                        const itemElement = document.getElementById(`cart-item-${itemId}`);
                        if (itemElement) itemElement.remove();

                        updateCartCounter();
                        updateSubmitButton();

                        if (cart.length === 0) {
                            emptyCart.style.display = 'block';
                        }

                        showToast('Tool dihapus dari daftar');
                    }
                }
            };

            // ==================== FORM SUBMISSION ====================
            document.getElementById('returnForm').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!selectedEngineer) {
                    showToast('Harap pilih engineer yang mengembalikan', 'error');
                    return;
                }

                if (cart.length === 0) {
                    showToast('Harap tambahkan setidaknya satu tool', 'error');
                    return;
                }

                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
                submitBtn.disabled = true;

                const formData = new FormData(this);

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
                    selectedTool = null;
                    cart = [];

                    if (!hasBorrow) {
                        selectedEngineerCard.style.display = 'none';
                        engineerSearch.value = '';
                        selectedEngineerId.value = '';
                    }

                    if (hasBorrow) {
                        const engineerId = @json($borrow->engineer->id ?? '');
                        const engineer = engineers.find(e => e.id == engineerId);
                        if (engineer) {
                            selectedEngineer = engineer;
                            selectedEngineerName.textContent = engineer.name;
                            selectedEngineerShift.textContent = engineer.shift || '';
                            selectedEngineerId.value = engineer.id;
                            selectedEngineerCard.style.display = 'block';
                            engineerSearch.value = engineer.name;
                        }
                    }

                    selectedToolCard.style.display = 'none';
                    toolSearch.value = '';

                    cartContainer.innerHTML = '';
                    emptyCart.style.display = 'block';

                    document.querySelector('input[name="job_reference"]').value = '';
                    document.querySelector('textarea[name="notes"]').value = '';

                    if (hasBorrow) {
                        document.querySelectorAll('.add-preloaded-tool').forEach(button => {
                            button.innerHTML = '<i class="bi bi-plus"></i> Tambah';
                            button.disabled = false;
                            button.classList.remove('btn-secondary');
                            button.classList.add('btn-success');
                        });
                    }

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
                if (!toolSearch.contains(e.target) && !toolResults.contains(e.target)) {
                    toolResults.style.display = 'none';
                }
            });

            // Initialize
            updateSubmitButton();
            if (hasBorrow && selectedEngineer && borrowDetails.length > 0) {
                updateSubmitButton();
            }
        });
    </script>
</body>

</html>
