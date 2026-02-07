<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
                            <i class="bi bi-tools me-2 text-primary"></i>Borrow Tools
                        </h1>
                        <p class="text-muted mb-0 small">Select engineer and tools to borrow</p>
                    </div>
                </div>

                <form id="borrowForm">
                    @csrf

                    <!-- Engineer Selection -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">1. Select Engineer</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="form-label fw-semibold">Search Engineer *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="engineerSearch"
                                        placeholder="Type engineer name..." autocomplete="off">
                                    <button class="btn btn-outline-secondary" type="button" id="clearEngineerBtn">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>

                                <!-- Search Results -->
                                <div class="list-group mt-2" id="engineerResults"
                                    style="display: none; max-height: 200px; overflow-y: auto;">
                                    <!-- Results will appear here -->
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
                        </div>
                    </div>

                    <!-- Tool Selection -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">2. Select Tools (Max 5)</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="form-label fw-semibold">Search Tool *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="toolSearch"
                                        placeholder="Type tool name..." autocomplete="off">
                                    <button class="btn btn-outline-secondary" type="button" id="clearToolBtn">
                                        <i class="bi bi-x"></i>
                                    </button>
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
                                        <h6 class="mb-2" id="selectedToolName"></h6>
                                        <p class="mb-1 small">
                                            <span class="text-muted">Code:</span>
                                            <span id="selectedToolCode" class="fw-semibold"></span>
                                        </p>
                                        <p class="mb-1 small">
                                            <span class="text-muted">Available:</span>
                                            <span id="selectedToolAvailable" class="badge bg-info"></span>
                                        </p>
                                        <p class="mb-0 small">
                                            <span class="text-muted">Location:</span>
                                            <span id="selectedToolLocation"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Quantity</label>
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
                                    <p class="small text-muted mb-2">
                                        Max: <span id="maxQuantity" class="fw-semibold">1</span> units
                                    </p>
                                    <button type="button" class="btn btn-success w-100" id="addToCart">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Section -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">3. Borrow Cart</h6>
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
                                    <i class="bi bi-cart-x display-6 mb-3 opacity-50"></i>
                                    <p class="mb-1">Cart is empty</p>
                                    <small>Add tools from above</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">4. Additional Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Job Reference</label>
                                    <input type="text" class="form-control" id="jobReference"
                                        placeholder="JOB-XXX / WO-XXX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Purpose *</label>
                                    <textarea class="form-control" id="purpose" rows="2" placeholder="What will the tools be used for?"
                                        required></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Expected Return Date *</label>
                                    <input type="date" class="form-control" id="returnDate"
                                        min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+7 days')) }}"
                                        required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Upload Photo (Optional)</label>
                                    <input type="file" class="form-control" id="borrowPhoto" accept="image/*">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Additional Notes</label>
                                    <textarea class="form-control" id="notes" rows="2" placeholder="Any additional information..."></textarea>
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
            // DUMMY DATA
            const dummyEngineers = [{
                    id: 1,
                    name: "John Doe",
                    code: "ENG-001",
                    shift: "Morning"
                },
                {
                    id: 2,
                    name: "Jane Smith",
                    code: "ENG-002",
                    shift: "Night"
                },
                {
                    id: 3,
                    name: "Robert Johnson",
                    code: "ENG-003",
                    shift: "Morning"
                },
                {
                    id: 4,
                    name: "Sarah Williams",
                    code: "ENG-004",
                    shift: "Night"
                }
            ];

            const dummyTools = [{
                    id: 1,
                    code: "HAM-001",
                    name: "Hammer",
                    current_quantity: 15,
                    current_locator: "Tool Room A"
                },
                {
                    id: 2,
                    code: "WRE-001",
                    name: "Wrench Set",
                    current_quantity: 8,
                    current_locator: "Tool Room B"
                },
                {
                    id: 3,
                    code: "DRI-001",
                    name: "Drill Machine",
                    current_quantity: 5,
                    current_locator: "Electrical Room"
                },
                {
                    id: 4,
                    code: "SAW-001",
                    name: "Circular Saw",
                    current_quantity: 3,
                    current_locator: "Carpentry Shop"
                },
                {
                    id: 5,
                    code: "MET-001",
                    name: "Tape Measure",
                    current_quantity: 25,
                    current_locator: "Tool Room A"
                }
            ];

            let cart = [];
            let selectedEngineer = null;
            let selectedTool = null;
            let maxTools = 5;

            // DOM Elements
            const engineerSearch = document.getElementById('engineerSearch');
            const engineerResults = document.getElementById('engineerResults');
            const selectedEngineerCard = document.getElementById('selectedEngineerCard');
            const selectedEngineerName = document.getElementById('selectedEngineerName');
            const selectedEngineerDetails = document.getElementById('selectedEngineerDetails');
            const selectedEngineerShift = document.getElementById('selectedEngineerShift');
            const clearEngineerBtn = document.getElementById('clearEngineerBtn');

            const toolSearch = document.getElementById('toolSearch');
            const toolResults = document.getElementById('toolResults');
            const selectedToolSection = document.getElementById('selectedToolSection');
            const selectedToolName = document.getElementById('selectedToolName');
            const selectedToolCode = document.getElementById('selectedToolCode');
            const selectedToolAvailable = document.getElementById('selectedToolAvailable');
            const selectedToolLocation = document.getElementById('selectedToolLocation');
            const toolQuantity = document.getElementById('toolQuantity');
            const maxQuantity = document.getElementById('maxQuantity');
            const clearToolBtn = document.getElementById('clearToolBtn');

            const addToCartBtn = document.getElementById('addToCart');
            const decreaseQtyBtn = document.getElementById('decreaseQty');
            const increaseQtyBtn = document.getElementById('increaseQty');

            const cartItems = document.getElementById('cartItems');
            const emptyCart = document.getElementById('emptyCart');
            const cartCount = document.getElementById('cartCount');
            const clearCartBtn = document.getElementById('clearCart');
            const submitBtn = document.getElementById('submitBtn');

            const toast = new bootstrap.Toast(document.getElementById('liveToast'));
            const toastMessage = document.getElementById('toastMessage');

            // Show toast message
            function showToast(message, type = 'success') {
                const icon = document.querySelector('#liveToast .toast-header i');
                if (type === 'success') {
                    icon.className = 'bi bi-check-circle-fill text-success me-2';
                } else if (type === 'error') {
                    icon.className = 'bi bi-exclamation-circle-fill text-danger me-2';
                } else {
                    icon.className = 'bi bi-info-circle-fill text-info me-2';
                }
                toastMessage.textContent = message;
                toast.show();
            }

            // Engineer Search
            engineerSearch.addEventListener('input', function(e) {
                const query = e.target.value.trim().toLowerCase();
                engineerResults.innerHTML = '';

                if (query.length < 1) {
                    engineerResults.style.display = 'none';
                    return;
                }

                const filtered = dummyEngineers.filter(engineer =>
                    engineer.name.toLowerCase().includes(query) ||
                    engineer.code.toLowerCase().includes(query)
                ).slice(0, 5); // Max 5 results

                if (filtered.length === 0) {
                    engineerResults.innerHTML = `
                        <div class="list-group-item text-muted">
                            No engineers found
                        </div>
                    `;
                } else {
                    filtered.forEach(engineer => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action text-start';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${engineer.name}</strong><br>
                                    <small class="text-muted">${engineer.code}</small>
                                </div>
                                <span class="badge bg-info">${engineer.shift}</span>
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
                selectedEngineerDetails.textContent = `Code: ${engineer.code}`;
                selectedEngineerShift.textContent = engineer.shift;
                selectedEngineerCard.style.display = 'block';
                engineerSearch.value = engineer.name;
                engineerResults.style.display = 'none';

                showToast(`Engineer ${engineer.name} selected`);
                updateSubmitButton();
            }

            // Clear Engineer
            clearEngineerBtn.addEventListener('click', function() {
                selectedEngineer = null;
                selectedEngineerCard.style.display = 'none';
                engineerSearch.value = '';
                updateSubmitButton();
            });

            // Tool Search
            toolSearch.addEventListener('input', function(e) {
                const query = e.target.value.trim().toLowerCase();
                toolResults.innerHTML = '';

                if (query.length < 1) {
                    toolResults.style.display = 'none';
                    return;
                }

                const filtered = dummyTools.filter(tool =>
                    tool.name.toLowerCase().includes(query) ||
                    tool.code.toLowerCase().includes(query)
                ).slice(0, 5); // Max 5 results

                if (filtered.length === 0) {
                    toolResults.innerHTML = `
                        <div class="list-group-item text-muted">
                            No tools found
                        </div>
                    `;
                } else {
                    filtered.forEach(tool => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action text-start';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${tool.name}</strong><br>
                                    <small class="text-muted">
                                        ${tool.code} | Available: ${tool.current_quantity}
                                    </small>
                                </div>
                                <span class="badge bg-light text-dark">${tool.current_locator}</span>
                            </div>
                        `;
                        item.addEventListener('click', () => selectTool(tool));
                        toolResults.appendChild(item);
                    });
                }
                toolResults.style.display = 'block';
            });

            // Select Tool
            function selectTool(tool) {
                selectedTool = tool;
                selectedToolName.textContent = tool.name;
                selectedToolCode.textContent = tool.code;
                selectedToolAvailable.textContent = tool.current_quantity;
                selectedToolLocation.textContent = tool.current_locator;

                toolQuantity.max = tool.current_quantity;
                toolQuantity.value = Math.min(1, tool.current_quantity);
                maxQuantity.textContent = tool.current_quantity;

                selectedToolSection.style.display = 'flex';
                toolSearch.value = tool.name;
                toolResults.style.display = 'none';
            }

            // Clear Tool Search
            clearToolBtn.addEventListener('click', function() {
                toolSearch.value = '';
                toolResults.style.display = 'none';
                selectedToolSection.style.display = 'none';
                selectedTool = null;
            });

            // Quantity Controls
            decreaseQtyBtn.addEventListener('click', function() {
                const current = parseInt(toolQuantity.value);
                if (current > 1) {
                    toolQuantity.value = current - 1;
                }
            });

            increaseQtyBtn.addEventListener('click', function() {
                const current = parseInt(toolQuantity.value);
                const max = parseInt(toolQuantity.max);
                if (current < max) {
                    toolQuantity.value = current + 1;
                }
            });

            toolQuantity.addEventListener('change', function() {
                let value = parseInt(this.value);
                const max = parseInt(this.max);
                const min = parseInt(this.min);

                if (isNaN(value) || value < min) value = min;
                if (value > max) {
                    value = max;
                    showToast(`Maximum available is ${max} units`, 'error');
                }

                this.value = value;
            });

            // Add to Cart
            addToCartBtn.addEventListener('click', function() {
                if (!selectedEngineer) {
                    showToast('Please select an engineer first', 'error');
                    return;
                }

                if (!selectedTool) {
                    showToast('Please select a tool first', 'error');
                    return;
                }

                if (cart.length >= maxTools) {
                    showToast(`Maximum ${maxTools} different tools allowed`, 'error');
                    return;
                }

                const toolId = selectedTool.id;
                const quantity = parseInt(toolQuantity.value);

                // Check if tool already in cart
                const existingIndex = cart.findIndex(item => item.tool_id === toolId);
                if (existingIndex > -1) {
                    // Update quantity
                    const newQty = cart[existingIndex].quantity + quantity;
                    const maxQty = selectedTool.current_quantity;

                    if (newQty > maxQty) {
                        showToast(`Cannot exceed available quantity of ${maxQty}`, 'error');
                        return;
                    }

                    cart[existingIndex].quantity = newQty;
                    updateCartItem(cart[existingIndex]);
                    showToast(`Updated ${selectedTool.name} quantity to ${newQty}`);
                } else {
                    // Add new item
                    const cartItem = {
                        id: Date.now(),
                        tool_id: toolId,
                        name: selectedTool.name,
                        code: selectedTool.code,
                        location: selectedTool.current_locator,
                        quantity: quantity,
                        max_quantity: selectedTool.current_quantity
                    };
                    cart.push(cartItem);
                    addCartItem(cartItem);
                    showToast(`${selectedTool.name} added to cart!`);
                }

                updateCartDisplay();
                clearToolBtn.click();
            });

            // Add Cart Item to UI
            function addCartItem(item) {
                const itemElement = document.createElement('div');
                itemElement.className = 'border-bottom p-3';
                itemElement.id = `cart-item-${item.id}`;
                itemElement.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <p class="mb-1 small text-muted">
                                Code: ${item.code} | Location: ${item.location}
                            </p>
                            <div class="d-flex align-items-center gap-2">
                                <div class="input-group input-group-sm" style="width: 140px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(${item.id}, -1)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number"
                                           class="form-control text-center"
                                           value="${item.quantity}"
                                           min="1"
                                           max="${item.max_quantity}"
                                           onchange="updateQuantity(${item.id}, 0, this.value)">
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(${item.id}, 1)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                <button class="btn btn-sm btn-outline-danger" type="button" onclick="removeFromCart(${item.id})">
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

            // Global functions for inline onclick handlers
            window.updateQuantity = function(itemId, change, newValue = null) {
                const index = cart.findIndex(item => item.id === itemId);
                if (index === -1) return;

                let newQty;
                if (newValue !== null) {
                    newQty = parseInt(newValue);
                } else {
                    newQty = cart[index].quantity + change;
                }

                if (newQty < 1) newQty = 1;
                if (newQty > cart[index].max_quantity) {
                    showToast(`Maximum available is ${cart[index].max_quantity}`, 'error');
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
                        showToast('Item removed from cart');
                    }
                }
            };

            // Clear Cart
            clearCartBtn.addEventListener('click', function() {
                if (cart.length > 0 && confirm('Clear all items from cart?')) {
                    cart = [];
                    updateCartDisplay();
                    showToast('Cart cleared');
                }
            });

            // Update Cart Display
            function updateCartDisplay() {
                // Remove all cart items
                document.querySelectorAll('#cartItems > div:not(#emptyCart)').forEach(el => el.remove());

                if (cart.length === 0) {
                    emptyCart.style.display = 'block';
                    clearCartBtn.disabled = true;
                } else {
                    emptyCart.style.display = 'none';
                    clearCartBtn.disabled = false;

                    // Re-add all items
                    cart.forEach(item => addCartItem(item));
                }

                updateCartCounter();
                updateSubmitButton();
            }

            // Update Cart Counter
            function updateCartCounter() {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                cartCount.textContent = cart.length;
            }

            // Update Submit Button State
            function updateSubmitButton() {
                submitBtn.disabled = !(selectedEngineer && cart.length > 0);
            }

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
                    document.getElementById('purpose').value = '';
                    document.getElementById('returnDate').value = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000)
                        .toISOString().split('T')[0];
                    document.getElementById('borrowPhoto').value = '';
                    document.getElementById('notes').value = '';

                    updateCartDisplay();
                    showToast('Form reset');
                }
            };

            // Form Submission
            document.getElementById('borrowForm').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!selectedEngineer) {
                    showToast('Please select an engineer', 'error');
                    return;
                }

                if (cart.length === 0) {
                    showToast('Please add at least one tool', 'error');
                    return;
                }

                const purpose = document.getElementById('purpose').value.trim();
                if (!purpose) {
                    showToast('Please enter purpose', 'error');
                    return;
                }

                const returnDate = document.getElementById('returnDate').value;
                if (!returnDate) {
                    showToast('Please select return date', 'error');
                    return;
                }

                // Prepare data
                const borrowData = {
                    engineer: selectedEngineer,
                    tools: cart,
                    purpose: purpose,
                    jobReference: document.getElementById('jobReference').value,
                    returnDate: returnDate,
                    notes: document.getElementById('notes').value
                };

                console.log('Borrow Data:', borrowData);

                // Show success message
                showToast('Borrow request submitted successfully!');

                // In real app, submit to server here
                setTimeout(() => {
                    alert(
                        `Borrow request for ${selectedEngineer.name} submitted!\n\nTools: ${cart.length} items\nPurpose: ${purpose}\nReturn Date: ${returnDate}`
                        );
                    resetForm();
                }, 1000);
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!engineerSearch.contains(e.target) && !engineerResults.contains(e.target)) {
                    engineerResults.style.display = 'none';
                }
                if (!toolSearch.contains(e.target) && !toolResults.contains(e.target)) {
                    toolResults.style.display = 'none';
                }
            });

            // Mobile: Make dropdowns scrollable
            if (window.innerWidth <= 768) {
                engineerResults.style.maxHeight = '40vh';
                toolResults.style.maxHeight = '40vh';
            }
        });
    </script>
</body>

</html>
