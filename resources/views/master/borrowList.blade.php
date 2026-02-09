@extends('layouts.app')
@php $currentPage = 'borrowList'; @endphp
@section('title', 'Peminjaman | Tools Management')
@section('content')
    <main class="app-main">
        <div class="app-content-header py-4 mb-4 bg-white border-bottom shadow-sm animate-fade-in">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-between mb-3 g-2">
                    <h1>Kelola Tools</h1>
                    <div class="col-auto d-flex flex-wrap align-items-end gap-3">

                        <div class="col-auto d-flex flex-wrap align-items-end gap-3">
                            {{-- toggle peminjaman complete --}}
                            <div>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('borrow.index') }}"
                                        class="btn {{ !$viewCompleted ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                        On Going
                                    </a>
                                    <a href="{{ route('borrow.index', ['is_completed' => 1]) }}"
                                        class="btn {{ $viewCompleted ? 'btn-success' : 'btn-outline-success' }}">
                                        Complete
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <form action="{{ route('borrow.index') }}" method="GET" id="filterForm">
                                @if (request('is_completed'))
                                    <input type="hidden" name="is_completed" value="{{ request('is_completed') }}">
                                @endif

                                <div class="row g-2 align-items-end">
                                    <!-- Filter items dengan ukuran berbeda -->
                                    <div class="col-md-2">
                                        <label class="form-label mb-1 small">Engineer</label>
                                        <div class="position-relative">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control pe-5" name="engineer_search"
                                                    placeholder="Cari..." autocomplete="off">
                                                <input type="hidden" name="engineer_id"
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

                                    <div class="col-md-2">
                                        <label class="form-label mb-1 small">Tool</label>
                                        <div class="position-relative">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control pe-5" name="tool_search"
                                                    placeholder="Cari..." autocomplete="off">
                                                <input type="hidden" name="tool_id" value="{{ request('tool_id') }}">
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

                                    <div class="col-md-2">
                                        <label class="form-label mb-1 small">Job Ref</label>
                                        <input type="text" class="form-control form-control-sm" name="job_reference"
                                            placeholder="Job reference" value="{{ request('job_reference') }}">
                                    </div>

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

                                    <div class="col-md-1">
                                        <label class="form-label mb-1 small">Urutkan</label>
                                        <select class="form-select form-select-sm" name="sort">
                                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                                Terbaru</option>
                                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                                Terlama</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1 d-flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                            <i class="bi bi-funnel"></i>
                                        </button>
                                        <a href="{{ route('borrow.index', ['is_completed' => request('is_completed')]) }}"
                                            class="btn btn-sm btn-outline-secondary w-100">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        @if ($borrows->isEmpty())
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 55vh">
                                <div class="text-center text-muted">
                                    <h4>Tidak Ada Data Peminjaman {{ $viewCompleted ? 'Selesai' : 'Berlangsung' }}</h4>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <div class="overflow-x-auto">
                                    <table class="table table-hover align-middle" style="min-width: 1200px;">
                                        <thead class="table-light">
                                            <tr style="vertical-align: middle">
                                                <th scope="col" style="min-width: 50px; width: 50px;">No</th>
                                                <th scope="col" style="min-width: 100px;">Prove Image</th>
                                                <th scope="col" style="min-width: 150px;">Tanggal</th>
                                                <th scope="col" style="min-width: 150px;">Peminjam</th>
                                                <th scope="col" style="min-width: 200px;">Job Reference</th>
                                                <th scope="col" style="min-width: 150px;">Tool List</th>
                                                <th scope="col" style="min-width: 150px;">Note</th>
                                                <th scope="col" style="min-width: 100px; width: 100px;"
                                                    class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="toolTableBody">
                                            @foreach ($borrows as $borrow)
                                                <tr>
                                                    <td style="min-width: 50px;">{{ $loop->iteration }}</td>
                                                    <td style="min-width: 100px;">
                                                        @if ($borrow->image)
                                                            <img src="{{ asset('storage/' . $borrow->image) }}"
                                                                alt="peminjaman {{ $borrow->engineer->name ?? 'No Engineer' }}"
                                                                class="rounded"
                                                                style="width: 75px; height: 75px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                                style="width: 75px; height: 75px;">
                                                                <i class="bi bi-bag text-muted fs-4"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span
                                                                class=" mb-1">{{ $borrow->created_at->format('d M Y') }}</span>
                                                            <small
                                                                class="text-muted">{{ $borrow->created_at->format('H:i') }}</small>
                                                        </div>
                                                    </td>
                                                    <td style="min-width: 150px;">
                                                        <strong>{{ $borrow->engineer->name ?? '-' }}</strong>
                                                    </td>
                                                    <td style="min-width: 200px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                        title="{{ $borrow->job_reference }}">
                                                        {{ $borrow->job_reference ?? '-' }}
                                                    </td>
                                                    <td style="min-width: 200px;">
                                                        <div class="d-flex flex-column gap-1">
                                                            @foreach ($borrow->borrowDetails as $detail)
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
                                                        </div>
                                                    </td>
                                                    <td style="min-width: 150px; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                        title="{{ $borrow->note }}">
                                                        {{ $borrow->note ?? '-' }}
                                                    </td>
                                                    <td style="min-width: 100px;" class="text-center">
                                                        <!-- Complete Button -->
                                                        @if (!$borrow->is_completed)
                                                            <form action="{{ route('borrow.complete', $borrow->id) }}"
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
                                                            <!-- Delete Button -->
                                                            {{-- <form action="{{ route('engineer.destroy', $engineer->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus engineer ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-link text-danger">
                                                                    <i class="bi bi-trash-fill"></i>
                                                                </button>
                                                            </form> --}}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr id="noResultRow" style="display: none;">
                                                <td colspan="12" class="text-center text-muted py-4">
                                                    <em>Tidak ada tool yang cocok dengan pencarian.</em>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </main>

    {{-- Skrip Preview & Filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil data dari PHP
            const allEngineers = @json($engineers);
            const allTools = @json($tools);

            // Engineer search elements
            const engineerSearch = document.querySelector('input[name="engineer_search"]');
            const engineerIdInput = document.querySelector('input[name="engineer_id"]');
            const engineerResults = document.getElementById('engineer-results');
            // Cari tombol clear yang ada di dalam input-group yang sama
            const engineerContainer = engineerSearch.closest('.input-group');
            const engineerClearBtn = engineerContainer.querySelector('.search-clear-btn');

            // Tool search elements
            const toolSearch = document.querySelector('input[name="tool_search"]');
            const toolIdInput = document.querySelector('input[name="tool_id"]');
            const toolResults = document.getElementById('tool-results');
            const toolContainer = toolSearch.closest('.input-group');
            const toolClearBtn = toolContainer.querySelector('.search-clear-btn');

            // Set initial values
            const initialEngineerId = engineerIdInput.value;
            const initialToolId = toolIdInput.value;

            // Set nilai awal jika sudah ada di request
            if (initialEngineerId) {
                const engineer = allEngineers.find(e => e.id == initialEngineerId);
                if (engineer) {
                    engineerSearch.value = engineer.name;
                    engineerClearBtn.style.display = 'block'; // Ubah dari 'flex' ke 'block'
                }
            }

            if (initialToolId) {
                const tool = allTools.find(t => t.id == initialToolId);
                if (tool) {
                    toolSearch.value = tool.name;
                    toolClearBtn.style.display = 'block'; // Ubah dari 'flex' ke 'block'
                }
            }

            // Fungsi untuk mencari engineer
            function searchEngineers(query) {
                query = query.toLowerCase().trim();
                if (query.length < 1) return [];

                return allEngineers
                    .filter(engineer =>
                        engineer.name.toLowerCase().includes(query)
                    )
                    .slice(0, 5);
            }

            // Fungsi untuk mencari tools
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

            // Fungsi untuk menampilkan results
            function showEngineerResults(query) {
                const results = searchEngineers(query);

                engineerResults.innerHTML = '';

                if (results.length === 0) {
                    if (query.length >= 1) {
                        const noResultDiv = document.createElement('div');
                        noResultDiv.className = 'search-select-result no-results';
                        noResultDiv.textContent = 'Tidak ditemukan';
                        engineerResults.appendChild(noResultDiv);
                    }
                } else {
                    results.forEach(engineer => {
                        const div = document.createElement('div');
                        div.className = 'search-select-result';
                        if (engineer.id == engineerIdInput.value) {
                            div.classList.add('selected');
                        }
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

            // Fungsi untuk menampilkan tool results
            function showToolResults(query) {
                const results = searchTools(query);

                toolResults.innerHTML = '';

                if (results.length === 0) {
                    if (query.length >= 1) {
                        const noResultDiv = document.createElement('div');
                        noResultDiv.className = 'search-select-result no-results';
                        noResultDiv.textContent = 'Tidak ditemukan';
                        toolResults.appendChild(noResultDiv);
                    }
                } else {
                    results.forEach(tool => {
                        const div = document.createElement('div');
                        div.className = 'search-select-result tool-result';
                        if (tool.id == toolIdInput.value) {
                            div.classList.add('selected');
                        }

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

            // Event listeners untuk engineer
            engineerSearch.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                showEngineerResults(query);

                // Tampilkan/sembunyikan tombol clear
                engineerClearBtn.style.display = query || engineerIdInput.value ? 'block' : 'none';

                // Jika input dikosongkan, hapus juga hidden value
                if (!query && !engineerIdInput.value) {
                    engineerIdInput.value = '';
                }
            });

            engineerSearch.addEventListener('focus', function() {
                if (this.value.trim() === '') {
                    showEngineerResults('');
                }
            });

            engineerClearBtn.addEventListener('click', function() {
                engineerSearch.value = '';
                engineerIdInput.value = '';
                engineerResults.style.display = 'none';
                this.style.display = 'none';
                engineerSearch.focus();
            });

            // Event listeners untuk tool
            toolSearch.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                showToolResults(query);

                // Tampilkan/sembunyikan tombol clear
                toolClearBtn.style.display = query || toolIdInput.value ? 'block' : 'none';

                // Jika input dikosongkan, hapus juga hidden value
                if (!query && !toolIdInput.value) {
                    toolIdInput.value = '';
                }
            });

            toolSearch.addEventListener('focus', function() {
                if (this.value.trim() === '') {
                    showToolResults('');
                }
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

                if (!engineerContainer.contains(e.target)) {
                    engineerResults.style.display = 'none';
                }

                if (!toolContainer.contains(e.target)) {
                    toolResults.style.display = 'none';
                }
            });

            // Sembunyikan tombol clear awal jika tidak ada nilai
            if (!engineerSearch.value && !engineerIdInput.value) {
                engineerClearBtn.style.display = 'none';
            }

            if (!toolSearch.value && !toolIdInput.value) {
                toolClearBtn.style.display = 'none';
            }
        });
    </script>
@endsection
