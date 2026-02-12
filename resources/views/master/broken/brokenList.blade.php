@extends('layouts.app')
@php
    $currentPage = 'brokenTools';
@endphp

@section('title', 'Broken Tools | Tools Management')
@section('content')
    <main class="app-main">
        <!-- Header -->
        <div class="app-content-header py-4 mb-4 bg-white border-bottom shadow-sm animate-fade-in">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-between mb-3 g-2">
                    <h1>Kelola Broken Tools</h1>
                    <div class="col-auto d-flex flex-wrap align-items-end gap-3">
                        {{-- Simple Toggle Button --}}
                        <div>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('broken.index') }}"
                                    class="btn {{ !$viewResolved ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                    <i class="bi bi-clock-history me-1"></i> On Going
                                </a>
                                <a href="{{ route('broken.index', ['status' => 'resolved']) }}"
                                    class="btn {{ $viewResolved ? 'btn-success' : 'btn-outline-success' }}">
                                    <i class="bi bi-check-circle me-1"></i> Resolved
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <form action="{{ route('broken.index') }}" method="GET" id="filterForm">
                            {{-- Hidden status untuk toggle --}}
                            <div class="row g-2 align-items-end">
                                {{-- FILTER 1: STATUS (HANYA UNTUK ONGOING) --}}
                                @if (!$viewResolved)
                                    <div class="col-md-2">
                                        <label class="form-label mb-1 small">Status</label>
                                        <select class="form-select form-select-sm" name="status_filter">
                                            <option value="">Semua Status</option>
                                            <option value="poor"
                                                {{ request('status_filter') == 'poor' ? 'selected' : '' }}>Poor</option>
                                            <option value="broken"
                                                {{ request('status_filter') == 'broken' ? 'selected' : '' }}>Broken</option>
                                            <option value="scrap"
                                                {{ request('status_filter') == 'scrap' ? 'selected' : '' }}>Scrap</option>
                                        </select>
                                    </div>
                                @endif

                                {{-- FILTER 2: ENGINEER (REPORTER / HANDLER) --}}
                                <div class="col-md-2">
                                    <label class="form-label mb-1 small">
                                        {{ $viewResolved ? 'Ditangani Oleh' : 'Dilaporkan Oleh' }}
                                    </label>
                                    <div class="position-relative">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control pe-5" name="engineer_search"
                                                id="engineerSearch"
                                                placeholder="Cari {{ $viewResolved ? 'Penangan' : 'Pelapor' }}"
                                                autocomplete="off" value="{{ request('engineer_search') }}">

                                            {{-- Hidden ID - beda nama sesuai tab --}}
                                            <input type="hidden" name="{{ $viewResolved ? 'handler_id' : 'reporter_id' }}"
                                                id="engineerId"
                                                value="{{ $viewResolved ? request('handler_id') : request('reporter_id') }}">

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

                                {{-- FILTER 3: TOOL --}}
                                <div class="col-md-2">
                                    <label class="form-label mb-1 small">Tool</label>
                                    <div class="position-relative">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control pe-5" name="tool_search"
                                                id="toolSearch" placeholder="Cari Tool" autocomplete="off"
                                                value="{{ request('tool_search') }}">
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

                                {{-- FILTER 4: DATE RANGE --}}
                                <div class="col-md-2">
                                    <label class="form-label mb-1 small">Dari</label>
                                    <input type="date" class="form-control form-control-sm" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label mb-1 small">Sampai</label>
                                    <input type="date" class="form-control form-control-sm" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>

                                {{-- FILTER 5: SORTING --}}
                                <div class="col-md-1">
                                    <label class="form-label mb-1 small">Urutkan</label>
                                    <select class="form-select form-select-sm" name="sort">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru
                                        </option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama
                                        </option>
                                    </select>
                                </div>

                                {{-- ACTION BUTTONS --}}
                                <div class="col-md-1 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary w-100" title="Filter">
                                        <i class="bi bi-funnel"></i>
                                    </button>
                                    <a href="{{ route('broken.index', ['status' => $viewResolved ? 'resolved' : null]) }}"
                                        class="btn btn-sm btn-outline-secondary w-100" title="Reset">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        @if ($brokenTools->isEmpty())
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 55vh">
                                <div class="text-center text-muted">
                                    <i class="bi bi-inbox display-1 mb-3"></i>
                                    <h4>Tidak Ada Data Laporan {{ $viewResolved ? 'Resolved' : 'Ongoing' }}</h4>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th style="width: 80px;">Gambar</th>
                                            <th>Tool</th>
                                            <th>Pelapor</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                            <th>Penangan</th>
                                            <th>Lokasi</th>
                                            <th>Tanggal</th>
                                            <th class="text-center" style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($brokenTools as $tool)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($tool->image)
                                                        <img src="{{ asset('storage/' . $tool->image) }}" alt="rusak"
                                                            class="rounded"
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                            style="width: 50px; height: 50px;">
                                                            <i class="bi bi-tools text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $tool->tool->name ?? '-' }}</strong>
                                                    <small
                                                        class="d-block text-muted">{{ $tool->tool->code ?? '-' }}</small>
                                                </td>
                                                <td>{{ $tool->reporter->name ?? '-' }}</td>
                                                <td><span class="badge bg-info">{{ $tool->quantity }}</span></td>
                                                <td>
                                                    @switch($tool->status)
                                                        @case('poor')
                                                            <span class="badge bg-warning">Poor</span>
                                                        @break

                                                        @case('broken')
                                                            <span class="badge bg-danger">Broken</span>
                                                        @break

                                                        @case('scrap')
                                                            <span class="badge bg-dark">Scrap</span>
                                                        @break

                                                        @case('resolved')
                                                            <span class="badge bg-success">Resolved</span>
                                                        @break

                                                        @default
                                                            <span class="badge bg-secondary">{{ $tool->status }}</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $tool->handler->name ?? '-' }}</td>
                                                <td><small>{{ $tool->locator ?? '-' }}</small></td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span
                                                            class="small">{{ $tool->created_at->format('d/m/Y') }}</span>
                                                        <small
                                                            class="text-muted">{{ $tool->created_at->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @if (!$viewResolved)
                                                        <a href="{{ route('broken.edit', $tool->id) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @else
                                                        <span class="badge bg-success">Selesai</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-4">
                                {{ $brokenTools->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari PHP
            const allEngineers = @json($engineers); // SEMUA engineer
            const allTools = @json($tools);
            const viewResolved = {{ $viewResolved ? 'true' : 'false' }};

            // ==================== ENGINEER SEARCH ====================
            const engineerSearch = document.getElementById('engineerSearch');
            const engineerIdInput = document.getElementById('engineerId'); // Ini bisa reporter_id atau handler_id
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
                engineerSearch.focus();
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
                toolSearch.focus();
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
        });
    </script>
@endsection
