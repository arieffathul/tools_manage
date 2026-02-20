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

<style>
    #engineer-results,
    #tool-results {
        border-color: #86b7fe !important;
    }

    .search-select-result {
        padding: 6px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
        font-size: 0.875rem;
    }

    .search-select-result:hover {
        background-color: #f8f9fa;
    }

    .search-select-result.selected {
        background-color: #e7f3ff;
    }

    .search-select-result:last-child {
        border-bottom: none;
    }

    .tool-name {
        font-weight: 500;
    }

    .tool-description {
        font-size: 0.75rem;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

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
                {{-- FILTER SECTION --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <form action="{{ route('broken.select') }}" method="GET" id="filterForm"
                            class="card shadow-sm p-3">
                            <div class="row g-3">
                                {{-- FILTER REPORTER (SEARCH & SELECT) --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Nama Pelapor</label>
                                    <div class="position-relative">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control pe-5" name="reporter_search"
                                                id="engineerSearch" placeholder="Cari engineer pelapor..."
                                                autocomplete="off" value="{{ request('reporter_search') }}">

                                            <input type="hidden" name="reporter_id" id="engineerId"
                                                value="{{ request('reporter_id') }}">

                                            <button type="button"
                                                class="btn btn-outline-secondary search-clear-btn position-absolute end-0 h-100"
                                                style="display: none; border: 1px solid #ced4da; border-left: none; z-index: 5; background: white;">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                        <div class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow-sm"
                                            style="z-index: 1050; max-height: 200px; overflow-y: auto; display: none; margin-top: -1px;"
                                            id="engineer-results"></div>
                                    </div>
                                </div>

                                {{-- FILTER TOOL (SEARCH & SELECT) --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Nama Tool</label>
                                    <div class="position-relative">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control pe-5" name="tool_search"
                                                id="toolSearch" placeholder="Cari nama atau deskripsi tool..."
                                                autocomplete="off" value="{{ request('tool_search') }}">

                                            <input type="hidden" name="tool_id" id="toolId"
                                                value="{{ request('tool_id') }}">

                                            <button type="button"
                                                class="btn btn-outline-secondary search-clear-btn position-absolute end-0 h-100"
                                                style="display: none; border: 1px solid #ced4da; border-left: none; z-index: 5; background: white;">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                        <div class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow-sm"
                                            style="z-index: 1050; max-height: 200px; overflow-y: auto; display: none; margin-top: -1px;"
                                            id="tool-results"></div>
                                    </div>
                                </div>

                                {{-- TOMBOL AKSI --}}
                                <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                                    <button type="submit" class="btn btn-primary btn-sm px-4">
                                        <i class="bi bi-funnel me-1"></i> Filter
                                    </button>
                                    @if (request('reporter_id') || request('tool_id') || request('reporter_search') || request('tool_search'))
                                        <a href="{{ route('broken.select') }}"
                                            class="btn btn-outline-danger btn-sm px-3">
                                            <i class="bi bi-x-circle me-1"></i> Reset
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- TOMBOL BUAT LAPORAN BARU --}}
                <div class="row mb-4">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{ route('broken.form') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Buat Laporan Baru
                        </a>
                    </div>
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
        // Data dari PHP
        const allEngineers = @json($engineers);
        const allTools = @json($tools);

        // ==================== ENGINEER SEARCH ====================
        const engineerSearch = document.getElementById('engineerSearch');
        const engineerIdInput = document.getElementById('engineerId');
        const engineerResults = document.getElementById('engineer-results');
        const engineerContainer = engineerSearch.closest('.input-group');
        const engineerClearBtn = engineerContainer.querySelector('.search-clear-btn');

        // ==================== TOOL SEARCH ====================
        const toolSearch = document.getElementById('toolSearch');
        const toolIdInput = document.getElementById('toolId');
        const toolResults = document.getElementById('tool-results');
        const toolContainer = toolSearch.closest('.input-group');
        const toolClearBtn = toolContainer.querySelector('.search-clear-btn');

        // ==================== SET INITIAL VALUES ====================
        if (engineerIdInput.value) {
            const engineer = allEngineers.find(e => e.id == engineerIdInput.value);
            if (engineer) {
                engineerSearch.value = engineer.name;
                engineerClearBtn.style.display = 'block';
            }
        }

        if (toolIdInput.value) {
            const tool = allTools.find(t => t.id == toolIdInput.value);
            if (tool) {
                toolSearch.value = tool.name;
                toolClearBtn.style.display = 'block';
            }
        }

        // ==================== ENGINEER SEARCH FUNCTION ====================
        function searchEngineers(query) {
            query = query.toLowerCase().trim();
            if (query.length < 1) return [];
            return allEngineers
                .filter(engineer => engineer.name.toLowerCase().includes(query))
                .slice(0, 5);
        }

        function showEngineerResults(query) {
            const results = searchEngineers(query);
            engineerResults.innerHTML = '';

            if (results.length === 0) {
                if (query.length >= 1) {
                    engineerResults.innerHTML =
                        '<div class="search-select-result no-results">Tidak ditemukan</div>';
                }
            } else {
                results.forEach(engineer => {
                    const div = document.createElement('div');
                    div.className = 'search-select-result';
                    if (engineer.id == engineerIdInput.value) div.classList.add('selected');
                    div.textContent = engineer.name;
                    div.dataset.id = engineer.id;
                    div.dataset.name = engineer.name;

                    div.addEventListener('click', function() {
                        engineerSearch.value = this.dataset.name;
                        engineerIdInput.value = this.dataset.id;
                        engineerResults.style.display = 'none';
                        engineerClearBtn.style.display = 'block';
                    });

                    engineerResults.appendChild(div);
                });
            }

            const shouldShow = results.length > 0 || (query.length >= 1 && results.length === 0);
            engineerResults.style.display = shouldShow ? 'block' : 'none';
        }

        // ==================== TOOL SEARCH FUNCTION ====================
        function searchTools(query) {
            query = query.toLowerCase().trim();
            if (query.length < 1) return [];
            return allTools
                .filter(tool =>
                    tool.name.toLowerCase().includes(query) ||
                    (tool.code && tool.code.toLowerCase().includes(query)) ||
                    (tool.description && tool.description.toLowerCase().includes(query))
                )
                .slice(0, 5);
        }

        function showToolResults(query) {
            const results = searchTools(query);
            toolResults.innerHTML = '';

            if (results.length === 0) {
                if (query.length >= 1) {
                    toolResults.innerHTML =
                        '<div class="search-select-result no-results">Tidak ditemukan</div>';
                }
            } else {
                results.forEach(tool => {
                    const div = document.createElement('div');
                    div.className = 'search-select-result tool-result';
                    if (tool.id == toolIdInput.value) div.classList.add('selected');
                    div.innerHTML = `
                    <div class="tool-name">${tool.name}</div>
                    <div class="tool-description">${tool.description || '-'}</div>
                `;
                    div.dataset.id = tool.id;
                    div.dataset.name = tool.name;

                    div.addEventListener('click', function() {
                        toolSearch.value = this.dataset.name;
                        toolIdInput.value = this.dataset.id;
                        toolResults.style.display = 'none';
                        toolClearBtn.style.display = 'block';

                    });

                    toolResults.appendChild(div);
                });
            }

            const shouldShow = results.length > 0 || (query.length >= 1 && results.length === 0);
            toolResults.style.display = shouldShow ? 'block' : 'none';
        }

        // ==================== EVENT LISTENERS ====================
        engineerSearch.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            showEngineerResults(query);
            engineerClearBtn.style.display = query || engineerIdInput.value ? 'block' : 'none';
            if (!query && !engineerIdInput.value) engineerIdInput.value = '';
        });

        engineerSearch.addEventListener('focus', function() {
            if (this.value.trim() === '') showEngineerResults('');
        });

        engineerClearBtn.addEventListener('click', function() {
            engineerSearch.value = '';
            engineerIdInput.value = '';
            engineerResults.style.display = 'none';
            this.style.display = 'none';
            document.getElementById('filterForm').submit();
        });

        toolSearch.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            showToolResults(query);
            toolClearBtn.style.display = query || toolIdInput.value ? 'block' : 'none';
            if (!query && !toolIdInput.value) toolIdInput.value = '';
        });

        toolSearch.addEventListener('focus', function() {
            if (this.value.trim() === '') showToolResults('');
        });

        toolClearBtn.addEventListener('click', function() {
            toolSearch.value = '';
            toolIdInput.value = '';
            toolResults.style.display = 'none';
            this.style.display = 'none';
            document.getElementById('filterForm').submit();
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            const engineerContainer = engineerSearch.closest('.position-relative');
            const toolContainer = toolSearch.closest('.position-relative');

            if (!engineerContainer.contains(e.target)) engineerResults.style.display = 'none';
            if (!toolContainer.contains(e.target)) toolResults.style.display = 'none';
        });

        // Initial clear button visibility
        if (!engineerSearch.value && !engineerIdInput.value) engineerClearBtn.style.display = 'none';
        if (!toolSearch.value && !toolIdInput.value) toolClearBtn.style.display = 'none';
    </script>
</body>

</html>
