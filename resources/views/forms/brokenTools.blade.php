<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broken Tools Form | Tools Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h4 fw-bold mb-1">
                            <i class="bi bi-tools me-2 text-warning"></i>
                            @if (isset($brokenTool))
                                Update Laporan Alat Rusak
                            @else
                                Laporan Alat Rusak
                            @endif
                        </h1>
                        <p class="text-muted mb-0 small">Laporakan alat yang rusak untuk ditindaklanjuti</p>
                    </div>
                    <a href="{{ route('broken.select') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>
                    </a>
                </div>

                <form id="brokenForm"
                    action="{{ isset($brokenTool) ? route('broken.update', $brokenTool->id) : route('broken.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($brokenTool))
                        @method('PUT')
                    @endif

                    <!-- 1. Keterangan Pemakaian -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-person me-2"></i>Keterangan Pemakaian</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Reported By (Search and Select) -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Dilaporkan Oleh *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="engineerSearch"
                                            placeholder="Masukkan Nama" autocomplete="off"
                                            value="{{ isset($brokenTool) ? $brokenTool->reportedBy->name ?? '' : '' }}">
                                    </div>

                                    <!-- Search Results -->
                                    <div class="list-group mt-2" id="engineerResults"
                                        style="display: none; max-height: 200px; overflow-y: auto;">
                                    </div>

                                    <!-- Selected Engineer -->
                                    <div class="border-start border-3 border-success ps-3 py-2 bg-light rounded mt-2"
                                        id="selectedEngineerCard"
                                        style="{{ isset($brokenTool) && $brokenTool->reported_by ? '' : 'display: none;' }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1" id="selectedEngineerName">
                                                    {{ isset($brokenTool) ? $brokenTool->reportedBy->name ?? '' : '' }}
                                                </h6>
                                                <small class="text-muted" id="selectedEngineerDetails">
                                                    {{ isset($brokenTool) ? $brokenTool->reportedBy->employee_id ?? '' : '' }}
                                                </small>
                                            </div>
                                            <span class="badge bg-success me-2" id="selectedEngineerShift">
                                                {{ isset($brokenTool) ? $brokenTool->reportedBy->shift ?? '' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="reported_by" id="selectedEngineerId"
                                        value="{{ $brokenTool->reported_by ?? old('reported_by') }}" required>
                                </div>

                                <!-- Last Used -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Pekerjaan Terakhir (Optional)</label>
                                    <input type="text" class="form-control" id="last_used" name="last_used"
                                        placeholder="Pekerjaan terakhir sebelum rusak"
                                        value="{{ $brokenTool->last_used ?? old('last_used') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Keterangan Tools -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-tools me-2"></i>Keterangan Tools</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Tool Selection -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Cari Tool *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="toolSearch"
                                            placeholder="Input Nama Tool atau Description" autocomplete="off"
                                            value="{{ isset($brokenTool) ? ($brokenTool->tool->name ?? '') . ' (Code: ' . ($brokenTool->tool->code ?? '') . ')' : '' }}">
                                    </div>

                                    <!-- Search Results -->
                                    <div class="list-group mt-2" id="toolResults"
                                        style="display: none; max-height: 200px; overflow-y: auto;">
                                    </div>

                                    <!-- Selected Tool -->
                                    <div class="border-start border-3 border-info ps-3 py-2 bg-light rounded mt-2"
                                        id="selectedToolSection"
                                        style="{{ isset($brokenTool) && $brokenTool->tool_id ? '' : 'display: none;' }}">
                                        <div class="d-flex align-items-start">
                                            <div class="shrink-0 me-3">
                                                <div id="selectedToolImageContainer">
                                                    @if (isset($brokenTool) && $brokenTool->tool && $brokenTool->tool->image)
                                                        <img src="{{ asset('storage/' . $brokenTool->tool->image) }}"
                                                            class="rounded"
                                                            style="width: 80px; height: 80px; object-fit: cover;"
                                                            alt="Tool Image">
                                                    @else
                                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                            style="width: 80px; height: 80px;">
                                                            <i class="bi bi-tools text-muted fs-4"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="grow">
                                                <h6 class="mb-1" id="selectedToolName">
                                                    {{ isset($brokenTool) ? $brokenTool->tool->name ?? '' : '' }}
                                                </h6>
                                                <p class="mb-1 small">
                                                    <span class="text-muted">Code:</span>
                                                    <span id="selectedToolCode" class="fw-semibold">
                                                        {{ isset($brokenTool) ? $brokenTool->tool->code ?? '' : '' }}
                                                    </span>
                                                </p>
                                                <p class="mb-1 small" id="selectedToolDescriptionContainer">
                                                    @if (isset($brokenTool) && $brokenTool->tool && $brokenTool->tool->description)
                                                        <span class="text-muted">Deskripsi:</span>
                                                        <span
                                                            class="fst-italic">{{ $brokenTool->tool->description }}</span>
                                                    @endif
                                                </p>
                                                <p class="mb-1 small">
                                                    <span class="text-muted">Jumlah Tersedia:</span>
                                                    <span id="selectedToolAvailable" class="badge bg-info">
                                                        {{ isset($brokenTool) ? $brokenTool->tool->quantity ?? '' : '' }}
                                                    </span>
                                                </p>
                                                <p class="mb-0 small">
                                                    <span class="text-muted">Locator:</span>
                                                    <span id="selectedToolLocator">
                                                        {{ isset($brokenTool) ? $brokenTool->tool->locator ?? '' : '' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="tool_id" id="selectedToolId"
                                        value="{{ $brokenTool->tool_id ?? old('tool_id') }}" required>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Jumlah Rusak *</label>
                                    <div class="input-group mb-2">
                                        <button class="btn btn-outline-secondary" type="button" id="decreaseQty">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control text-center" id="quantity"
                                            name="quantity"
                                            value="{{ $brokenTool->quantity ?? (old('quantity') ?? 0) }}"
                                            min="1" max="1">
                                        <button class="btn btn-outline-secondary" type="button" id="increaseQty">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Locator (After Report) -->
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Locator Setelah Dipindahkan*</label>
                                    <input type="text" class="form-control" id="locator" name="locator"
                                        placeholder="Contoh: A.1.1.1"
                                        value="{{ $brokenTool->locator ?? old('locator') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Keterangan Kerusakan -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Keterangan
                                Kerusakan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Image Upload -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Foto Kerusakan*</label>
                                    <input type="file" class="form-control" id="image" name="image"
                                        accept="image/*" onchange="previewImage(event)" required>

                                    <!-- Image Preview -->
                                    @if (isset($brokenTool) && $brokenTool->image)
                                        <div class="mt-3">
                                            <small class="text-muted">Foto saat ini:</small>
                                            <div class="border rounded p-2 bg-light mt-1">
                                                <img src="{{ asset('storage/' . $brokenTool->image) }}"
                                                    class="img-fluid rounded"
                                                    style="max-height: 200px; object-fit: contain;"
                                                    alt="Foto kerusakan saat ini">
                                                <div class="mt-2">
                                                    <small class="text-muted">Jika upload foto baru, foto ini akan
                                                        diganti</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-3" id="imagePreviewContainer" style="display: none;">
                                        <div class="border rounded p-2 bg-light">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">Preview Foto Baru:</small>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="removeImagePreview()">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>
                                            <div class="text-center">
                                                <img id="imagePreview" class="img-fluid rounded"
                                                    style="max-height: 200px; object-fit: contain;"
                                                    alt="Preview foto kerusakan">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Issue & Action -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Deskripsi Kerusakan*</label>
                                        <textarea class="form-control" id="issue" name="issue" rows="3"
                                            placeholder="Jelaskan kerusakan yang terjadi" required>{{ $brokenTool->issue ?? old('issue') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tindakan yang Diambil*</label>
                                        <input type="text" class="form-control" id="action" name="action"
                                            placeholder="Tindakan yang akan atau telah diambil"
                                            value="{{ $brokenTool->action ?? old('action') }}" required>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status Kerusakan *</label>
                                    <select class="form-select" id="status" name="status" required
                                        onchange="toggleResolvedFields()">
                                        <option value="" disabled selected
                                            style="display: none; font-size: 14px;">
                                            Pilih Status Kerusakan
                                        </option>
                                        <option value="poor"
                                            {{ (isset($brokenTool) && $brokenTool->status == 'poor') || old('status') == 'poor' ? 'selected' : '' }}
                                            style="font-size: 14px;">
                                            Poor - Masih bisa dipakai
                                        </option>
                                        <option value="broken"
                                            {{ (isset($brokenTool) && $brokenTool->status == 'broken') || old('status') == 'broken' ? 'selected' : '' }}
                                            style="font-size: 14px;">
                                            Broken - Tidak bisa dipakai
                                        </option>
                                        <option value="scrap"
                                            {{ (isset($brokenTool) && $brokenTool->status == 'scrap') || old('status') == 'scrap' ? 'selected' : '' }}
                                            style="font-size: 14px;">
                                            Scrap - Tidak bisa diperbaiki
                                        </option>
                                        @if (isset($brokenTool))
                                            <option value="resolved"
                                                {{ (isset($brokenTool) && $brokenTool->status == 'resolved') || old('status') == 'resolved' ? 'selected' : '' }}
                                                style="font-size: 14px;">
                                                Resolved - Telah diperbaiki
                                            </option>
                                        @endif
                                    </select>
                                </div>

                                <!-- Handled By (Hanya untuk status Resolved) -->
                                <div class="col-md-6" id="handledBySection" style="display: none;">
                                    <label class="form-label fw-semibold">Ditangani Oleh</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="handledBySearch"
                                            placeholder="Cari Engineer" autocomplete="off">
                                    </div>

                                    <!-- Search Results -->
                                    <div class="list-group mt-2" id="handledByResults"
                                        style="display: none; max-height: 200px; overflow-y: auto;">
                                    </div>

                                    <!-- Selected Handled By Engineer -->
                                    <div class="border-start border-3 border-primary ps-3 py-2 bg-light rounded mt-2"
                                        id="selectedHandledByCard" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1" id="selectedHandledByName"></h6>
                                                <small class="text-muted" id="selectedHandledByDetails"></small>
                                            </div>
                                            <span class="badge bg-primary me-2" id="selectedHandledByShift"></span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="handled_by" id="selectedHandledById">
                                </div>

                                <!-- Hidden Resolved At -->
                                <input type="hidden" name="resolved_at" id="resolved_at">

                                <!-- Notes -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Catatan Tambahan</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"
                                        placeholder="Tulis catatan tambahan jika diperlukan">{{ $brokenTool->notes ?? old('notes') }}</textarea>
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
                            <button type="submit" class="btn btn-success w-100" id="submitBtn">
                                <i class="bi bi-check-circle"></i>
                                @if (isset($brokenTool))
                                    Update Laporan
                                @else
                                    Simpan Laporan
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Engineers data from PHP
            const engineers = @json($engineers);
            const toolsData = @json($tools);

            // ==================== ENGINEER SEARCH FUNCTIONS ====================
            let selectedEngineer = null;
            let selectedHandledBy = null;

            // Reported By elements
            const engineerSearch = document.getElementById('engineerSearch');
            const engineerResults = document.getElementById('engineerResults');
            const selectedEngineerCard = document.getElementById('selectedEngineerCard');
            const selectedEngineerName = document.getElementById('selectedEngineerName');
            const selectedEngineerDetails = document.getElementById('selectedEngineerDetails');
            const selectedEngineerShift = document.getElementById('selectedEngineerShift');
            const selectedEngineerId = document.getElementById('selectedEngineerId');

            // Handled By elements
            const handledBySearch = document.getElementById('handledBySearch');
            const handledByResults = document.getElementById('handledByResults');
            const selectedHandledByCard = document.getElementById('selectedHandledByCard');
            const selectedHandledByName = document.getElementById('selectedHandledByName');
            const selectedHandledByDetails = document.getElementById('selectedHandledByDetails');
            const selectedHandledByShift = document.getElementById('selectedHandledByShift');
            const selectedHandledById = document.getElementById('selectedHandledById');
            const handledBySection = document.getElementById('handledBySection');

            // ==================== TOOL SEARCH FUNCTIONS ====================
            let selectedTool = null;
            const toolSearch = document.getElementById('toolSearch');
            const toolResults = document.getElementById('toolResults');
            const selectedToolSection = document.getElementById('selectedToolSection');
            const selectedToolName = document.getElementById('selectedToolName');
            const selectedToolCode = document.getElementById('selectedToolCode');
            const selectedToolAvailable = document.getElementById('selectedToolAvailable');
            const selectedToolLocator = document.getElementById('selectedToolLocator');
            const selectedToolDescriptionContainer = document.getElementById('selectedToolDescriptionContainer');
            const selectedToolImageContainer = document.getElementById('selectedToolImageContainer');
            const selectedToolId = document.getElementById('selectedToolId');
            const quantityInput = document.getElementById('quantity');
            const decreaseQtyBtn = document.getElementById('decreaseQty');
            const increaseQtyBtn = document.getElementById('increaseQty');

            const submitBtn = document.getElementById('submitBtn');


            // ==================== INITIALIZE FORM ====================
            // Initialize status fields
            toggleResolvedFields();

            // ==================== PRE-SELECT DATA FOR EDIT MODE ====================
            // Pre-select reported engineer if editing
            @if (isset($brokenTool) && $brokenTool->reported_by)
                const reportedEngineerId = {{ $brokenTool->reported_by }};
                const reportedEngineer = engineers.find(e => e.id == reportedEngineerId);
                if (reportedEngineer) {
                    selectedEngineer = reportedEngineer;
                    engineerSearch.value = reportedEngineer.name;
                    selectedEngineerName.textContent = reportedEngineer.name;
                    selectedEngineerDetails.textContent = reportedEngineer.employee_id || '';
                    selectedEngineerShift.textContent = reportedEngineer.shift || '';
                    selectedEngineerCard.style.display = 'block';
                    selectedEngineerId.value = reportedEngineer.id;
                }
            @endif

            // Pre-select handled by engineer if editing
            @if (isset($brokenTool) && $brokenTool->handled_by)
                const handledEngineerId = {{ $brokenTool->handled_by }};
                const handledEngineer = engineers.find(e => e.id == handledEngineerId);
                if (handledEngineer) {
                    selectedHandledBy = handledEngineer;
                    handledBySearch.value = handledEngineer.name;
                    selectedHandledByName.textContent = handledEngineer.name;
                    selectedHandledByDetails.textContent = handledEngineer.employee_id || '';
                    selectedHandledByShift.textContent = handledEngineer.shift || '';
                    selectedHandledByCard.style.display = 'block';
                    selectedHandledById.value = handledEngineer.id;
                    handledBySection.style.display = 'block';
                }
            @endif

            // Pre-select tool if editing
            @if (isset($brokenTool) && $brokenTool->tool_id)
                const selectedToolIdValue = {{ $brokenTool->tool_id }};
                const selectedToolData = toolsData.find(t => t.id == selectedToolIdValue);
                if (selectedToolData) {
                    selectedTool = selectedToolData;
                    toolSearch.value = `${selectedToolData.name} (Code: ${selectedToolData.code})`;
                    selectedToolName.textContent = selectedToolData.name;
                    selectedToolCode.textContent = selectedToolData.code;
                    selectedToolAvailable.textContent = selectedToolData.quantity;
                    selectedToolLocator.textContent = selectedToolData.locator || '-';
                    selectedToolId.value = selectedToolData.id;
                    selectedToolSection.style.display = 'block';

                    // Update description
                    if (selectedToolData.description) {
                        selectedToolDescriptionContainer.innerHTML = `
                        <span class="text-muted">Deskripsi:</span>
                        <span class="fst-italic">${selectedToolData.description}</span>
                    `;
                        selectedToolDescriptionContainer.style.display = 'block';
                    }

                    // Update image
                    if (selectedToolData.image) {
                        selectedToolImageContainer.innerHTML = `
                        <img src="/storage/${selectedToolData.image}"
                             class="rounded"
                             style="width: 80px; height: 80px; object-fit: cover;"
                             alt="${selectedToolData.name}">
                    `;
                    }

                    // Set max quantity
                    quantityInput.max = selectedToolData.quantity;
                }
            @endif

            // ==================== REPORTED BY ENGINEER SEARCH ====================
            engineerSearch.addEventListener('input', function(e) {
                const query = e.target.value.trim().toLowerCase();
                engineerResults.innerHTML = '';

                if (query.length < 1) {
                    engineerResults.style.display = 'none';
                    return;
                }

                const filtered = engineers.filter(engineer =>
                    engineer.name.toLowerCase().includes(query) ||
                    (engineer.employee_id && engineer.employee_id.toLowerCase().includes(query))
                ).slice(0, 5);

                if (filtered.length === 0) {
                    engineerResults.innerHTML =
                        `<div class="list-group-item text-muted">Tidak ada engineer ditemukan</div>`;
                } else {
                    filtered.forEach(engineer => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action text-start';
                        item.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${engineer.name}</strong><br>
                                <small class="text-muted">${engineer.employee_id || ''}</small>
                            </div>
                            ${engineer.shift ? `<span class="badge bg-info">${engineer.shift}</span>` : ''}
                        </div>
                    `;
                        item.addEventListener('click', () => selectReportedEngineer(engineer));
                        engineerResults.appendChild(item);
                    });
                }
                engineerResults.style.display = 'block';
            });

            function selectReportedEngineer(engineer) {
                selectedEngineer = engineer;
                selectedEngineerName.textContent = engineer.name;
                selectedEngineerDetails.textContent = engineer.employee_id || '';
                selectedEngineerShift.textContent = engineer.shift || '';
                selectedEngineerCard.style.display = 'block';
                engineerSearch.value = engineer.name;
                engineerResults.style.display = 'none';
                selectedEngineerId.value = engineer.id;
            }

            // ==================== HANDLED BY ENGINEER SEARCH ====================
            handledBySearch.addEventListener('input', function(e) {
                const query = e.target.value.trim().toLowerCase();
                handledByResults.innerHTML = '';

                if (query.length < 1) {
                    handledByResults.style.display = 'none';
                    return;
                }

                const filtered = engineers.filter(engineer =>
                    engineer.name.toLowerCase().includes(query) ||
                    (engineer.employee_id && engineer.employee_id.toLowerCase().includes(query))
                ).slice(0, 5);

                if (filtered.length === 0) {
                    handledByResults.innerHTML =
                        `<div class="list-group-item text-muted">Tidak ada engineer ditemukan</div>`;
                } else {
                    filtered.forEach(engineer => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action text-start';
                        item.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${engineer.name}</strong><br>
                                <small class="text-muted">${engineer.employee_id || ''}</small>
                            </div>
                            ${engineer.shift ? `<span class="badge bg-info">${engineer.shift}</span>` : ''}
                        </div>
                    `;
                        item.addEventListener('click', () => selectHandledByEngineer(engineer));
                        handledByResults.appendChild(item);
                    });
                }
                handledByResults.style.display = 'block';
            });

            function selectHandledByEngineer(engineer) {
                selectedHandledBy = engineer;
                selectedHandledByName.textContent = engineer.name;
                selectedHandledByDetails.textContent = engineer.employee_id || '';
                selectedHandledByShift.textContent = engineer.shift || '';
                selectedHandledByCard.style.display = 'block';
                handledBySearch.value = engineer.name;
                handledByResults.style.display = 'none';
                selectedHandledById.value = engineer.id;
            }

            // ==================== TOOL SEARCH ====================
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

                    return name.includes(query) ||
                        code.includes(query) ||
                        description.includes(query);
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
                                    `<img src="/storage/${tool.image}" alt="${tool.name || 'Tool'}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">` :
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
                                                <i class="bi bi-box"></i> stock: ${tool.quantity || 0}
                                            </span>
                                            <span>
                                                <i class="bi bi-geo-alt"></i> ${tool.locator || 'Unknown'}
                                            </span>
                                        </div>
                                        ${tool.description ? `<p class="mb-1 small text-truncate" style="max-width: 300px;">${tool.description}</p>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                        item.addEventListener('click', (event) => {
                            event.preventDefault();
                            selectTool(tool);
                        });

                        toolResults.appendChild(item);
                    });
                }
                toolResults.style.display = 'block';
            });

            // ==================== TOOL SELECTION ====================
            function selectTool(tool) {
                if (!tool) {
                    console.error('No tool selected');
                    return;
                }

                if (!tool.id) {
                    console.error('Tool missing ID:', tool);
                    return;
                }

                selectedTool = tool;

                // Update text content
                selectedToolName.textContent = tool.name || 'No Name';
                selectedToolCode.textContent = tool.code || '-';
                selectedToolAvailable.textContent = tool.quantity || 0;
                selectedToolLocator.textContent = tool.locator || 'Unknown';

                // Update image
                if (tool.image) {
                    selectedToolImageContainer.innerHTML = `
                    <img src="/storage/${tool.image}" alt="${tool.name || 'Tool'}"
                         class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                `;
                } else {
                    selectedToolImageContainer.innerHTML = `
                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-tools text-muted fs-4"></i>
                    </div>
                `;
                }

                // Update description
                if (tool.description) {
                    selectedToolDescriptionContainer.innerHTML = `
                    <span class="text-muted">Deskripsi:</span>
                    <span class="fst-italic">${tool.description}</span>
                `;
                    selectedToolDescriptionContainer.style.display = 'block';
                } else {
                    selectedToolDescriptionContainer.style.display = 'none';
                }

                // Set max quantity and update input
                const maxQty = parseInt(tool.quantity) || 1;
                quantityInput.max = maxQty;
                quantityInput.value = Math.min(parseInt(quantityInput.value) || 1, maxQty);

                // Show selected tool section
                selectedToolSection.style.display = 'block';
                toolSearch.value = tool.name || '';
                toolResults.style.display = 'none';
                selectedToolId.value = tool.id;
            }

            // ==================== QUANTITY CONTROLS ====================
            decreaseQtyBtn.addEventListener('click', function() {
                const current = parseInt(quantityInput.value);
                if (current > 1) quantityInput.value = current - 1;
            });

            increaseQtyBtn.addEventListener('click', function() {
                const current = parseInt(quantityInput.value);
                const max = parseInt(quantityInput.max);
                if (current < max) quantityInput.value = current + 1;
            });

            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                const max = parseInt(this.max);
                const min = parseInt(this.min);

                if (isNaN(value) || value < min) value = min;
                if (value > max) {
                    value = max;
                    alert(`Jumlah melebihi jumlah tersedia (${max})`);
                }
                this.value = value;
            });

            // ==================== CLOSE DROPDOWNS WHEN CLICKING OUTSIDE ====================
            document.addEventListener('click', function(e) {
                // Engineer search
                if (!engineerSearch.contains(e.target) && !engineerResults.contains(e.target)) {
                    engineerResults.style.display = 'none';
                }

                // Handled by search
                if (!handledBySearch.contains(e.target) && !handledByResults.contains(e.target)) {
                    handledByResults.style.display = 'none';
                }

                // Tool search
                if (!toolSearch.contains(e.target) && !toolResults.contains(e.target)) {
                    toolResults.style.display = 'none';
                }
            });

            // ==================== CLEAR SELECTIONS WHEN SEARCH IS CLEARED ====================
            engineerSearch.addEventListener('blur', function() {
                if (this.value === '' && selectedEngineerId.value === '') {
                    selectedEngineerCard.style.display = 'none';
                }
            });

            handledBySearch.addEventListener('blur', function() {
                if (this.value === '' && selectedHandledById.value === '') {
                    selectedHandledByCard.style.display = 'none';
                }
            });

            toolSearch.addEventListener('blur', function() {
                if (this.value === '' && selectedToolId.value === '') {
                    selectedToolSection.style.display = 'none';
                }
            });

            // ==================== FORM VALIDATION ====================

            document.getElementById('brokenForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // ========== VALIDASI ==========
                const toolId = document.getElementById('selectedToolId').value;
                const reportedBy = document.getElementById('selectedEngineerId').value;
                const quantity = document.getElementById('quantity').value;
                const status = document.getElementById('status').value;

                // Get available quantity from selected tool
                let availableQty = 0;
                if (selectedTool) {
                    availableQty = selectedTool.quantity;
                }

                // Basic validation
                if (!toolId || !reportedBy || !quantity || !status) {
                    alert('Harap isi semua field yang wajib diisi (*)');
                    return;
                }

                // Quantity validation
                if (parseInt(quantity) > parseInt(availableQty)) {
                    alert(
                        `Jumlah rusak (${quantity}) tidak boleh melebihi jumlah tersedia (${availableQty})`
                    );
                    return;
                }

                // If status is resolved, handled_by is required
                if (status === 'resolved' && !document.getElementById('selectedHandledById').value) {
                    alert('Harap pilih engineer yang menangani untuk status "Resolved"');
                    return;
                }

                // ========== LOADING STATE ==========
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';

                // ========== FORM DATA ==========
                const formData = new FormData();

                // Hidden method untuk update
                @if (isset($brokenTool))
                    formData.append('_method', 'PUT');
                @endif

                // Basic fields
                formData.append('tool_id', toolId);
                formData.append('reported_by', reportedBy);
                formData.append('quantity', quantity);
                formData.append('status', status);
                formData.append('locator', document.getElementById('locator').value);
                formData.append('last_used', document.getElementById('last_used').value);
                formData.append('issue', document.getElementById('issue').value);
                formData.append('action', document.getElementById('action').value);
                formData.append('notes', document.getElementById('notes').value);

                // Handled by (if resolved)
                if (document.getElementById('selectedHandledById').value) {
                    formData.append('handled_by', document.getElementById('selectedHandledById').value);
                }

                // Resolved at (if auto-filled)
                if (document.getElementById('resolved_at').value) {
                    formData.append('resolved_at', document.getElementById('resolved_at').value);
                }

                // Image upload
                const imageInput = document.getElementById('image');
                if (imageInput.files[0]) {
                    formData.append('image', imageInput.files[0]);
                }

                // ========== CSRF TOKEN ==========
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Ganti this.action dengan:
                const formAction =
                    "{{ isset($brokenTool) ? route('broken.update', $brokenTool->id) : route('broken.store') }}";

                fetch(formAction, {
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
                            setTimeout(() => {
                                window.location.href = data.redirect ||
                                    '{{ route('forms.complete') }}';
                            }, 1500);
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan data');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });

            });
        });

        // Make selectedTool global for validation
        // window.selectedTool = selectedTool;

        // ==================== TOGGLE RESOLVED FIELDS ====================
        function toggleResolvedFields() {
            const status = document.getElementById('status').value;
            const handledBySection = document.getElementById('handledBySection');
            const resolvedAtField = document.getElementById('resolved_at');

            if (status === 'resolved') {
                // Show handled by section
                handledBySection.style.display = 'block';

                // Auto-set resolved_at with current time
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                resolvedAtField.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            } else {
                // Hide handled by section
                handledBySection.style.display = 'none';
            }
        }

        // ==================== IMAGE PREVIEW FUNCTIONS ====================
        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('imagePreviewContainer');
            const preview = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.style.display = 'none';
            }
        }

        function removeImagePreview() {
            const input = document.getElementById('image');
            const previewContainer = document.getElementById('imagePreviewContainer');

            input.value = '';
            previewContainer.style.display = 'none';
        }

        // Reset Form Function
        function resetForm() {
            if (confirm('Apakah Anda yakin ingin membatalkan? Semua data yang belum disimpan akan hilang.')) {
                // Reset engineer selection
                selectedEngineer = null;
                document.getElementById('engineerSearch').value = '';
                document.getElementById('selectedEngineerCard').style.display = 'none';
                document.getElementById('selectedEngineerId').value = '';

                // Reset tool selection
                selectedTool = null;
                document.getElementById('toolSearch').value = '';
                document.getElementById('selectedToolSection').style.display = 'none';
                document.getElementById('selectedToolId').value = '';
                document.getElementById('quantity').value = '0';
                document.getElementById('quantity').max = '1';

                // Reset handled by selection
                selectedHandledBy = null;
                document.getElementById('handledBySearch').value = '';
                document.getElementById('selectedHandledByCard').style.display = 'none';
                document.getElementById('selectedHandledById').value = '';
                document.getElementById('handledBySection').style.display = 'none';

                // Reset form fields
                document.getElementById('last_used').value = '';
                document.getElementById('locator').value = '';
                document.getElementById('issue').value = '';
                document.getElementById('action').value = '';
                document.getElementById('notes').value = '';

                // Reset status dropdown
                document.getElementById('status').selectedIndex = 0;
                toggleResolvedFields();

                // Reset image
                document.getElementById('image').value = '';
                document.getElementById('imagePreviewContainer').style.display = 'none';

                // Reset hidden resolved_at
                document.getElementById('resolved_at').value = '';

                // Close any open dropdowns
                document.getElementById('engineerResults').style.display = 'none';
                document.getElementById('toolResults').style.display = 'none';
                document.getElementById('handledByResults').style.display = 'none';

                // Scroll to top
                window.scrollTo(0, 0);

                // If editing mode, redirect back to select page
                @if (isset($brokenTool))
                    window.location.href = "{{ route('broken.select') }}";
                @endif
            }
        }

        // Make resetForm globally accessible
        window.resetForm = resetForm;
    </script>
</body>

</html>
