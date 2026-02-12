@extends('layouts.app')
@php
    $currentPage = 'borrowList';
@endphp
@section('title', $viewCompleted ? 'Pengembalian | Tools Management' : 'Peminjaman | Tools Management')
@section('content')
    <main class="app-main">
        <div class="app-content-header py-4 mb-4 bg-white border-bottom shadow-sm animate-fade-in">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-between mb-3 g-2">
                    <h1>{{ $viewCompleted ? 'Riwayat Pengembalian' : 'Kelola Peminjaman' }}</h1>
                    <div class="col-auto d-flex flex-wrap align-items-end gap-3">
                        {{-- toggle peminjaman complete --}}
                        <div>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('borrow.index', ['is_completed' => 0]) }}"
                                    class="btn {{ !$viewCompleted ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                    <i class="bi bi-clock-history me-1"></i> On Going
                                </a>
                                <a href="{{ route('borrow.index', ['is_completed' => 1]) }}"
                                    class="btn {{ $viewCompleted ? 'btn-success' : 'btn-outline-success' }}">
                                    <i class="bi bi-check-circle me-1"></i> Complete
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <form action="{{ route('borrow.index') }}" method="GET" id="filterForm">
                            <input type="hidden" name="is_completed" value="{{ request('is_completed', 0) }}">

                            <div class="row g-2 align-items-end">
                                <!-- Filter Engineer / Returner -->
                                <div class="col-md-2">
                                    <label class="form-label mb-1 small">
                                        {{ $viewCompleted ? 'Pengembali' : 'Peminjam' }}
                                    </label>
                                    <div class="position-relative">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control pe-5" name="engineer_search"
                                                id="engineerSearch"
                                                placeholder="Cari {{ $viewCompleted ? 'Pengembali' : 'Engineer' }}"
                                                autocomplete="off">
                                            <input type="hidden" name="engineer_id" id="engineerId"
                                                value="{{ request('engineer_id') }}">
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

                                <!-- Filter Tool -->
                                <div class="col-md-2">
                                    <label class="form-label mb-1 small">Tool</label>
                                    <div class="position-relative">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control pe-5" name="tool_search"
                                                id="toolSearch" placeholder="Cari Tool" autocomplete="off">
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

                                <!-- Filter Job Ref -->
                                <div class="col-md-2">
                                    <label class="form-label mb-1 small">Job Ref</label>
                                    <input type="text" class="form-control form-control-sm" name="job_reference"
                                        placeholder="Job reference" value="{{ request('job_reference') }}">
                                </div>

                                <!-- Filter Date Range -->
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

                                <!-- Sorting (hanya untuk On Going) -->
                                @if (!$viewCompleted)
                                    <div class="col-md-1">
                                        <label class="form-label mb-1 small">Urutkan</label>
                                        <select class="form-select form-select-sm" name="sort">
                                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                                Terbaru</option>
                                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                                Terlama</option>
                                        </select>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="col-md-1 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary w-100" title="Filter">
                                        <i class="bi bi-funnel"></i>
                                    </button>
                                    <a href="{{ route('borrow.index', ['is_completed' => request('is_completed', 0)]) }}"
                                        class="btn btn-sm btn-outline-secondary w-100" title="Reset">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        @if ($borrows->isEmpty())
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 55vh">
                                <div class="text-center text-muted">
                                    <i class="bi bi-inbox display-1 mb-3"></i>
                                    <h4>Tidak Ada Data {{ $viewCompleted ? 'Pengembalian' : 'Peminjaman Berlangsung' }}
                                    </h4>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <div class="overflow-x-auto">
                                    <table class="table table-hover align-middle" style="min-width: 1200px;">
                                        <thead class="table-light">
                                            <tr style="vertical-align: middle">
                                                <th style="min-width: 50px; width: 50px;">No</th>
                                                <th style="min-width: 100px;">Prove Image</th>
                                                <th style="min-width: 150px;">Tanggal</th>
                                                <th style="min-width: 150px;">
                                                    {{ $viewCompleted ? 'Pengembali' : 'Peminjam' }}</th>
                                                <th style="min-width: 200px;">Job Reference</th>
                                                <th style="min-width: 150px;">Tool List</th>
                                                <th style="min-width: 150px;">Note</th>
                                                <th style="min-width: 100px;" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($borrows as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>

                                                    <!-- Image -->
                                                    <td>
                                                        @if ($viewCompleted)
                                                            {{-- Tampilkan gambar dari return pertama --}}
                                                            @php
                                                                $returnImage =
                                                                    $item->returnDetails->first()->image ?? null;
                                                            @endphp
                                                            @if ($returnImage)
                                                                <img src="{{ asset('storage/' . $returnImage) }}"
                                                                    alt="pengembalian" class="rounded"
                                                                    style="width: 75px; height: 75px; object-fit: cover;">
                                                            @else
                                                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                                    style="width: 75px; height: 75px;">
                                                                    <i class="bi bi-tools text-muted fs-4"></i>
                                                                </div>
                                                            @endif
                                                        @else
                                                            @if ($item->image)
                                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                                    alt="peminjaman" class="rounded"
                                                                    style="width: 75px; height: 75px; object-fit: cover;">
                                                            @else
                                                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                                    style="width: 75px; height: 75px;">
                                                                    <i class="bi bi-bag text-muted fs-4"></i>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </td>

                                                    <!-- Date -->
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span
                                                                class="mb-1">{{ $item->created_at->format('d M Y') }}</span>
                                                            <small
                                                                class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                                                        </div>
                                                    </td>

                                                    <!-- Engineer/Returner -->
                                                    <td>
                                                        <strong>
                                                            @if ($viewCompleted)
                                                                {{ $item->returner->name ?? ($item->borrow->engineer->name ?? '-') }}
                                                            @else
                                                                {{ $item->engineer->name ?? '-' }}
                                                            @endif
                                                        </strong>
                                                    </td>

                                                    <!-- Job Reference -->
                                                    <td title="{{ $item->job_reference }}">
                                                        {{ $item->job_reference ?? '-' }}
                                                    </td>

                                                    <!-- Tool List -->
                                                    <td>
                                                        <div class="d-flex flex-column gap-1">
                                                            @if ($viewCompleted)
                                                                @foreach ($item->returnDetails as $detail)
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                                        <div>
                                                                            <strong>{{ $detail->tool->name ?? 'Unknown' }}</strong>
                                                                            @if ($detail->tool && $detail->tool->description)
                                                                                <small class="d-block text-muted">
                                                                                    {{ Str::limit($detail->tool->description, 30) }}
                                                                                </small>
                                                                            @endif
                                                                            @if ($detail->locator)
                                                                                <small class="d-block text-muted">
                                                                                    <i class="bi bi-geo-alt"></i>
                                                                                    {{ $detail->locator }}
                                                                                </small>
                                                                            @endif
                                                                        </div>
                                                                        <span
                                                                            class="badge bg-success ms-2">{{ $detail->quantity }}</span>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                @foreach ($item->borrowDetails as $detail)
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                                        <div>
                                                                            <strong>{{ $detail->tool->name ?? 'Unknown' }}</strong>
                                                                            @if ($detail->tool && $detail->tool->description)
                                                                                <small class="d-block text-muted">
                                                                                    {{ Str::limit($detail->tool->description, 30) }}
                                                                                </small>
                                                                            @endif
                                                                        </div>
                                                                        <span
                                                                            class="badge bg-info ms-2">{{ $detail->quantity }}</span>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <!-- Note -->
                                                    <td
                                                        title="{{ $viewCompleted ? $item->notes ?? '-' : $item->note ?? '-' }}">
                                                        {{ $viewCompleted ? $item->notes ?? '-' : $item->note ?? '-' }}
                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="text-center">
                                                        @if (!$viewCompleted)
                                                            <form action="{{ route('borrow.complete', $item->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Tandai peminjaman ini sebagai selesai?')">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button class="btn btn-sm btn-success w-100"
                                                                    title="Complete">
                                                                    <i class="bi bi-check-circle me-1"></i> Complete
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="badge bg-success">Selesai</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-4">
                                {{ $borrows->appends(request()->query())->links() }}
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
            const allEngineers = @json($viewCompleted ? $returners : $engineers);
            const allTools = @json($tools);
            const viewCompleted = {{ $viewCompleted ? 'true' : 'false' }};

            // ==================== ENGINEER/RETURNER SEARCH ====================
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

    <style>
        .search-select-result {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .search-select-result:hover {
            background-color: #f8f9fa;
        }

        .search-select-result.selected {
            background-color: #e7f1ff;
        }

        .search-select-result.no-results {
            color: #6c757d;
            cursor: default;
        }

        .search-select-result.no-results:hover {
            background-color: transparent;
        }

        .tool-result {
            padding: 8px 12px;
        }

        .tool-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .tool-description {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
@endsection
