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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==================== GLOBAL VARIABLES ====================
            let cart = [];
            let selectedEngineer = null;
            let selectedTool = null;

            // Data from Laravel
            const engineers = @json($engineers ?? []);
            const toolsData = @json($tools ?? []);

            // DOM Elements - Engineer Section
            const engineerSearch = document.getElementById('engineerSearch');
            const engineerResults = document.getElementById('engineerResults');
            const selectedEngineerCard = document.getElementById('selectedEngineerCard');
            const selectedEngineerName = document.getElementById('selectedEngineerName');
            const selectedEngineerDetails = document.getElementById('selectedEngineerDetails');
            const selectedEngineerShift = document.getElementById('selectedEngineerShift');

            // DOM Elements - Tool Section
            const toolSearch = document.getElementById('toolSearch');
            const toolResults = document.getElementById('toolResults');
            const selectedToolSection = document.getElementById('selectedToolSection');
            const selectedToolName = document.getElementById('selectedToolName');
            const selectedToolCode = document.getElementById('selectedToolCode');
            const selectedToolAvailable = document.getElementById('selectedToolAvailable');
            const selectedToolLocator = document.getElementById('selectedToolLocator');
            const toolQuantity = document.getElementById('toolQuantity');

            // DOM Elements - Cart Section
            const addToCartBtn = document.getElementById('addToCart');
            const decreaseQtyBtn = document.getElementById('decreaseQty');
            const increaseQtyBtn = document.getElementById('increaseQty');
            const cartItems = document.getElementById('cartItems');
            const emptyCart = document.getElementById('emptyCart');
            const cartCount = document.getElementById('cartCount');
            const clearCartBtn = document.getElementById('clearCart');
            const submitBtn = document.getElementById('submitBtn');

            // input foto
            const borrowPhoto = document.getElementById('borrowPhoto');
            const photoPreviewContainer = document.getElementById('photoPreviewContainer');
            const photoPreview = document.getElementById('photoPreview');
            const photoInfo = document.getElementById('photoInfo');
            const removePhotoBtn = document.getElementById('removePhotoBtn');

            // Toast
            const toast = new bootstrap.Toast(document.getElementById('liveToast'));
            const toastMessage = document.getElementById('toastMessage');

            // ==================== UTILITY FUNCTIONS ====================
            // ==================== UTILITY FUNCTIONS ====================
            function showToast(message, type = 'success') {
                const icon = document.querySelector('#liveToast .toast-header i');
                const header = document.querySelector('#liveToast .toast-header strong');

                // Set icon based on type
                if (type === 'success') {
                    icon.className = 'bi bi-check-circle-fill text-success me-2';
                    header.textContent = 'Success';
                    toastMessage.style.color = 'green';
                } else if (type === 'error') {
                    icon.className = 'bi bi-exclamation-circle-fill text-danger me-2';
                    header.textContent = 'Error';
                    toastMessage.style.color = 'red';
                } else {
                    icon.className = 'bi bi-info-circle-fill text-info me-2';
                    header.textContent = 'Info';
                    toastMessage.style.color = 'blue';
                }

                toastMessage.textContent = message;
                toast.show();
            }

            function updateSubmitButton() {
                submitBtn.disabled = !(selectedEngineer && cart.length > 0);
            }

            function updateCartCounter() {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                cartCount.textContent = cart.length;
            }

            // ==================== ENGINEER FUNCTIONS ====================
            // Engineer Search
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
                        `<div class="list-group-item text-muted">No engineers found</div>`;
                } else {
                    filtered.forEach(engineer => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action text-start';
                        item.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${engineer.name}</strong><br>
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

            // Select Engineer
            function selectEngineer(engineer) {
                selectedEngineer = engineer;
                selectedEngineerName.textContent = engineer.name;
                selectedEngineerShift.textContent = engineer.shift || '';
                selectedEngineerCard.style.display = 'block';
                engineerSearch.value = engineer.name;
                engineerResults.style.display = 'none';
                showToast(`Engineer ${engineer.name} selected`);
                updateSubmitButton();
            }

            // ==================== TOOL FUNCTIONS ====================
            // Tool Search - Fixed version
            toolSearch.addEventListener('input', function(e) {
                const query = e.target.value.trim().toLowerCase();
                toolResults.innerHTML = '';

                if (query.length < 1) {
                    toolResults.style.display = 'none';
                    return;
                }

                // Check if toolsData is an array
                if (!Array.isArray(toolsData)) {
                    console.error('toolsData is not an array:', toolsData);
                    toolResults.innerHTML =
                        `<div class="list-group-item text-danger">Error: Tools data format incorrect</div>`;
                    toolResults.style.display = 'block';
                    return;
                }

                const filtered = toolsData.filter(tool => {
                    // Check if tool object exists
                    if (!tool || typeof tool !== 'object') {
                        return false;
                    }

                    const name = (tool.name || '').toLowerCase();
                    const code = (tool.code || '').toLowerCase();
                    const description = (tool.description || '').toLowerCase();
                    const spec = (tool.spec || '').toLowerCase();

                    return name.includes(query) ||
                        code.includes(query) ||
                        description.includes(query) ||
                        spec.includes(query);
                }).slice(0, 5);

                if (filtered.length === 0) {
                    toolResults.innerHTML =
                        `<div class="list-group-item text-muted">Tidak ada tool ditemukan</div>`;
                } else {
                    filtered.forEach(tool => {
                        const item = document.createElement('a');
                        item.href = 'javascript:void(0)';
                        item.className = 'list-group-item list-group-item-action text-start p-2';
                        item.innerHTML = `
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        ${tool.image ?
                            `<img src="${tool.image}" alt="${tool.name || 'Tool'}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">` :
                            `<div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                                                                                                                                                                                                                                                <i class="bi bi-tools text-muted fs-5"></i>
                                                                                                                                                                                                                                                                            </div>`
                        }
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong class="mb-1">${tool.name || 'No Name'}</strong>
                                <div class="small text-muted mb-1">
                                    <span class="me-2">
                                        <i class="bi bi-tag"></i> ${tool.code || '-'}
                                    </span>
                                    <span class="me-2">
                                        <i class="bi bi-box"></i> stock: ${tool.current_quantity || 0}
                                    </span>
                                    <span>
                                        <i class="bi bi-geo-alt"></i> ${tool.current_locator || 'Unknown'}
                                    </span>
                                </div>
                                ${tool.description ? `<p class="mb-1 small text-truncate" style="max-width: 300px;">${tool.description}</p>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;

                        // Add click event listener
                        item.addEventListener('click', (event) => {
                            event.preventDefault();
                            // console.log('Tool clicked:', tool);
                            selectTool(tool);
                        });

                        toolResults.appendChild(item);
                    });
                }
                toolResults.style.display = 'block';
            });

            // Select Tool function
            // Select Tool function
            function selectTool(tool) {
                if (!tool) {
                    console.error('No tool selected');
                    showToast('Pilih tool yang tersedia', 'error');
                    return;
                }

                // console.log('Selected tool object:', tool);

                // Check if tool has required properties
                if (!tool.id) {
                    console.error('Tool missing ID:', tool);
                    showToast('Data tool tidak lengkap', 'error');
                    return;
                }

                selectedTool = tool;

                // Update text content
                selectedToolName.textContent = tool.name || 'No Name';
                selectedToolCode.textContent = tool.code || '-';
                selectedToolAvailable.textContent = tool.current_quantity || 0;
                selectedToolLocator.textContent = tool.current_locator || 'Unknown';

                // Update image
                const imageContainer = document.getElementById('selectedToolImageContainer');
                if (tool.image) {
                    imageContainer.innerHTML = `
            <img src="${tool.image}" alt="${tool.name || 'Tool'}"
                 class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
        `;
                } else {
                    imageContainer.innerHTML = `
            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                 style="width: 80px; height: 80px;">
                <i class="bi bi-tools text-muted fs-4"></i>
            </div>
        `;
                }

                // Update description
                const descriptionContainer = document.getElementById('selectedToolDescriptionContainer');
                if (tool.description) {
                    descriptionContainer.innerHTML = `
            <span class="text-muted">Deskripsi:</span>
            <span class="fst-italic">${tool.description}</span>
        `;
                    descriptionContainer.style.display = 'block';
                } else {
                    descriptionContainer.style.display = 'none';
                }

                const maxQty = parseInt(tool.quantity) || 1;
                toolQuantity.max = maxQty;
                toolQuantity.value = Math.min(1, maxQty);

                selectedToolSection.style.display = 'flex';
                toolSearch.value = tool.name || '';
                toolResults.style.display = 'none';

                showToast(`${tool.name || 'Tool'} dipilih`);
            }

            // Make selectTool globally accessible
            window.selectTool = selectTool;

            // Quantity Controls
            decreaseQtyBtn.addEventListener('click', function() {
                const current = parseInt(toolQuantity.value);
                if (current > 1) toolQuantity.value = current - 1;
            });

            increaseQtyBtn.addEventListener('click', function() {
                const current = parseInt(toolQuantity.value);
                const max = parseInt(toolQuantity.max);
                if (current < max) toolQuantity.value = current + 1;
            });

            toolQuantity.addEventListener('change', function() {
                let value = parseInt(this.value);
                const max = parseInt(this.max);
                const min = parseInt(this.min);

                if (isNaN(value) || value < min) value = min;
                if (value > max) {
                    value = max;
                    showToast(`Jumlah melebihi jumlah tersedia (${max})`, 'error');
                }
                this.value = value;
            });

            // ==================== CART FUNCTIONS ====================
            // Add to Cart
            addToCartBtn.addEventListener('click', function() {


                if (!selectedTool) {
                    showToast('Harap pilih tool terlebih dahulu', 'error');
                    return;
                }

                const toolId = selectedTool.id;
                const quantity = parseInt(toolQuantity.value);

                // Check if tool already in cart
                const existingIndex = cart.findIndex(item => item.tool_id === toolId);
                if (existingIndex > -1) {
                    const newQty = cart[existingIndex].quantity + quantity;
                    const maxQty = selectedTool.quantity;

                    if (newQty > maxQty) {
                        showToast(`Jumlah melebihi jumlah tersedia (${maxQty})`, 'error');
                        return;
                    }

                    cart[existingIndex].quantity = newQty;
                    updateCartItem(cart[existingIndex]);
                    showToast(`Jumlah berhasil diperbarui di keranjang!`);
                } else {
                    const cartItem = {
                        id: Date.now(),
                        tool_id: toolId,
                        name: selectedTool.name,
                        code: selectedTool.code || '-',
                        Locator: selectedTool.current_locator,
                        quantity: quantity,
                        max_quantity: selectedTool.quantity,
                        image: selectedTool.image || null,
                        description: selectedTool.description || null
                    };
                    cart.push(cartItem);
                    addCartItem(cartItem);
                    showToast(`${selectedTool.name} ditambahkan ke keranjang!`);
                }

                updateCartDisplay();
            });
            // Add Cart Item to UI
            function addCartItem(item) {
                const itemElement = document.createElement('div');
                itemElement.className = 'border-bottom p-3';
                itemElement.id = `cart-item-${item.id}`;

                // Gunakan item.image bukan selectedTool.image
                itemElement.innerHTML = `
        <div class="d-flex align-items-start">
            <div class="flex-shrink-0 me-3">
                ${item.image ?
                    `<img src="${item.image}" alt="${item.name}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">` :
                    `<div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                                                                                                                                                        <i class="bi bi-tools text-muted"></i>
                                                                                                                                                                                                    </div>`
                }
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="mb-1">${item.name}</h6>
                        <p class="mb-1 small text-muted">
                            <i class="bi bi-tag"></i> ${item.code} |
                            <i class="bi bi-geo-alt"></i> ${item.Locator}
                        </p>
                        ${item.description ? `<p class="mb-1 small text-muted">${item.description}</p>` : ''}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="width: 140px;">
                        <button class="btn btn-outline-secondary" onclick="window.updateQuantity(${item.id}, -1)">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number"
                               class="form-control text-center"
                               value="${item.quantity}"
                               min="1"
                               max="${item.max_quantity}"
                               onchange="window.updateQuantity(${item.id}, 0, this.value)">
                        <button class="btn btn-outline-secondary" onclick="window.updateQuantity(${item.id}, 1)">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="window.removeFromCart(${item.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

                if (emptyCart.style.display !== 'none') {
                    emptyCart.style.display = 'none';
                }
                cartItems.appendChild(itemElement);
            }

            // Update Cart Item
            function updateCartItem(item) {
                const itemElement = document.getElementById(`cart-item-${item.id}`);
                if (itemElement) {
                    const input = itemElement.querySelector('input[type="number"]');
                    input.value = item.quantity;
                    input.max = item.max_quantity;
                }
            }

            // Global cart functions (for inline onclick)
            window.updateQuantity = function(itemId, change, newValue = null) {
                const index = cart.findIndex(item => item.id === itemId);
                if (index === -1) return;

                let newQty = newValue !== null ? parseInt(newValue) : cart[index].quantity + change;

                if (newQty < 1) newQty = 1;
                if (newQty > cart[index].max_quantity) {
                    showToast(`Jumlah melebihi jumlah tersedia (${cart[index].max_quantity})`, 'error');
                    newQty = cart[index].max_quantity;
                }

                cart[index].quantity = newQty;
                updateCartItem(cart[index]);
                updateCartCounter();
            };

            window.removeFromCart = function(itemId) {
                if (confirm('Remove this item from cart?')) {
                    const index = cart.findIndex(item => item.id === itemId);
                    if (index > -1) {
                        cart.splice(index, 1);
                        document.getElementById(`cart-item-${itemId}`).remove();
                        updateCartDisplay();
                        showToast('Item dihapus dari keranjang');
                    }
                }
            };

            // Clear Cart
            clearCartBtn.addEventListener('click', function() {
                if (cart.length > 0 && confirm('Kosongkan keranjang?')) {
                    cart = [];
                    updateCartDisplay();
                    showToast('Keranjang dibersihkan');
                }
            });

            // Update Cart Display
            function updateCartDisplay() {
                document.querySelectorAll('#cartItems > div:not(#emptyCart)').forEach(el => el.remove());

                if (cart.length === 0) {
                    emptyCart.style.display = 'block';
                    clearCartBtn.disabled = true;
                } else {
                    emptyCart.style.display = 'none';
                    clearCartBtn.disabled = false;
                    cart.forEach(item => addCartItem(item));
                }

                updateCartCounter();
                updateSubmitButton();
            }

            // ==================== PHOTO UPLOAD FUNCTIONS ====================
            // Photo Preview
            // Handle photo upload with preview
            borrowPhoto.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (!file) {
                    photoPreviewContainer.style.display = 'none';
                    return;
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    showToast('File harus berupa gambar (JPG, PNG, dll)', 'error');
                    borrowPhoto.value = '';
                    photoPreviewContainer.style.display = 'none';
                    return;
                }

                // Validate file size (max 5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    showToast('Ukuran file maksimal 5MB', 'error');
                    borrowPhoto.value = '';
                    photoPreviewContainer.style.display = 'none';
                    return;
                }

                // Create preview
                const reader = new FileReader();

                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreviewContainer.style.display = 'block';

                    // Show file info
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                    photoInfo.textContent = `${file.name} • ${fileSize} MB • ${file.type}`;

                    showToast('Foto berhasil diupload, lihat preview di bawah', 'success');
                };

                reader.onerror = function() {
                    showToast('Gagal membaca file foto', 'error');
                    photoPreviewContainer.style.display = 'none';
                };

                reader.readAsDataURL(file);
            });

            // Handle remove photo button
            removePhotoBtn.addEventListener('click', function() {
                borrowPhoto.value = '';
                photoPreviewContainer.style.display = 'none';
                showToast('Foto dihapus', 'info');
            });

            // Close preview when clicking outside (optional)
            document.addEventListener('click', function(e) {
                if (!borrowPhoto.contains(e.target) && !photoPreviewContainer.contains(e.target)) {
                    // Keep preview visible if there's a file
                    if (!borrowPhoto.value) {
                        photoPreviewContainer.style.display = 'none';
                    }
                }
            });

            // ==================== FORM FUNCTIONS ====================
            // Reset Form
            window.resetForm = function() {
                if (confirm('Reset form? All data will be lost.')) {
                    selectedEngineer = null;
                    selectedTool = null;
                    cart = [];

                    // Reset UI
                    selectedEngineerCard.style.display = 'none';
                    selectedToolSection.style.display = 'none';
                    engineerSearch.value = '';
                    toolSearch.value = '';
                    document.getElementById('jobReference').value = '';
                    document.getElementById('borrowPhoto').value = '';
                    document.getElementById('note').value = '';

                    updateCartDisplay();
                    showToast('Form reset');
                }
            };

            // Form Submission
            // Form Submission
            document.getElementById('borrowForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Validation
                if (!selectedEngineer) {
                    showToast('Harap pilih engineer', 'error');
                    return;
                }

                if (cart.length === 0) {
                    showToast('Harap tambahkan setidaknya satu tool', 'error');
                    return;
                }

                if (!borrowPhoto.files[0]) {
                    showToast('Harap upload foto sebelum submit', 'error');
                    return;
                }

                // Create FormData
                const formData = new FormData();
                formData.append('engineer_id', selectedEngineer.id);
                formData.append('job_reference', document.getElementById('jobReference').value);
                formData.append('note', document.getElementById('notes').value);
                formData.append('image', borrowPhoto.files[0]);

                // Add details
                cart.forEach((item, index) => {
                    formData.append(`details[${index}][tool_id]`, item.tool_id);
                    formData.append(`details[${index}][quantity]`, item.quantity);
                });

                // Show loading
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
                submitBtn.disabled = true;

                // Ambil CSRF token dari meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Submit via AJAX
                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Data peminjaman berhasil disimpan!', 'success');
                            setTimeout(() => {
                                window.location.href = data.redirect || 'form/complete';
                            }, 1500);
                        } else {
                            showToast(data.message || 'Terjadi kesalahan', 'error');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan saat menyimpan data', 'error');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });

            // ==================== EVENT LISTENERS ====================
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!engineerSearch.contains(e.target) && !engineerResults.contains(e.target)) {
                    engineerResults.style.display = 'none';
                }
                if (!toolSearch.contains(e.target) && !toolResults.contains(e.target)) {
                    toolResults.style.display = 'none';
                }
            });

            // Mobile adjustments
            if (window.innerWidth <= 768) {
                engineerResults.style.maxHeight = '40vh';
                toolResults.style.maxHeight = '40vh';
            }

            // Initialize
            updateSubmitButton();
        });
    </script>
</body>

</html>
