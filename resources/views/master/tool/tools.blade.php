@extends('layouts.app')
@php $currentPage = 'tools'; @endphp
@section('title', 'Tools | Tools Management')
@section('content')
    <main class="app-main">
        <div class="app-content-header py-4 mb-4 bg-white border-bottom shadow-sm animate-fade-in">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-between mb-3 g-2">
                    <h1>Kelola Tools</h1>
                    <div class="col-auto d-flex flex-wrap align-items-end gap-3">
                        {{-- Search --}}
                        <div>
                            <label class="form-label mb-1 small">Cari Tool</label>
                            <form action="{{ route('tool.index') }}" method="GET" id="searchForm">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" name="search"
                                        placeholder="Masukkan nama, kode, atau desc tool" id="searchTool"
                                        value="{{ request('search') }}">
                                    <button class="btn btn-success" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- Tombol Tambah --}}
                    <div class="col-auto">
                        <button type="button" class="btn btn-success d-flex align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#addToolModal">
                            <i class="bi bi-plus-lg"></i> Tambah Tool
                        </button>
                    </div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        @if ($tools->isEmpty())
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 55vh">
                                <div class="text-center text-muted">
                                    <h4>Tidak Ada Data Tool</h4>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <div class="overflow-x-auto">
                                    <table class="table table-hover align-middle" style="min-width: 1200px;">
                                        <thead class="table-light">
                                            <tr style="vertical-align: middle">
                                                <th scope="col" style="min-width: 50px; width: 50px;">No</th>
                                                <th scope="col" style="min-width: 100px;">Image</th>
                                                <th scope="col" style="min-width: 100px;">Code</th>
                                                <th scope="col" style="min-width: 150px;">Tool Name</th>
                                                <th scope="col" style="min-width: 200px;">Description</th>
                                                <th scope="col" style="min-width: 150px;">Specification</th>
                                                <th scope="col" style="min-width: 120px;">Locator</th>
                                                <th scope="col" style="min-width: 120px;">Current Locator</th>
                                                <th scope="col" style="min-width: 100px;">Quantity</th>
                                                <th scope="col" style="min-width: 120px;">Current Quantity</th>
                                                <th scope="col" style="min-width: 150px;">Last Audited</th>
                                                <th scope="col" style="min-width: 100px; width: 100px;"
                                                    class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="toolTableBody">
                                            @foreach ($tools as $tool)
                                                <tr data-locator="{{ $tool->current_locator }}">
                                                    <td style="min-width: 50px;">{{ $loop->iteration }}</td>
                                                    <td style="min-width: 100px;">
                                                        @if ($tool->image)
                                                            <img src="{{ asset('storage/' . $tool->image) }}"
                                                                alt="{{ $tool->name }}" class="rounded"
                                                                style="width: 75px; height: 75px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                                style="width: 75px; height: 75px;">
                                                                <i class="bi bi-tools text-muted fs-4"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 100px;">
                                                        <span class="badge bg-primary">{{ $tool->code ?? '-' }}</span>
                                                    </td>
                                                    <td style="min-width: 150px;">
                                                        <strong>{{ $tool->name }}</strong>
                                                    </td>
                                                    <td style="min-width: 200px; max-width: 200px; flex-wrap: wrap;"
                                                        title="{{ $tool->description }}">
                                                        {{ $tool->description ?? '-' }}
                                                    </td>
                                                    <td style="min-width: 150px; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                        title="{{ $tool->spec }}">
                                                        {{ $tool->spec ?? '-' }}
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        {{ $tool->locator ?? '-' }}
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        {{ $tool->current_locator }}
                                                    </td>
                                                    <td style="min-width: 100px;">
                                                        <span class="badge bg-secondary">{{ $tool->quantity }}</span>
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        @if ($tool->current_quantity == 0)
                                                            <span class="badge bg-warning">0</span>
                                                        @elseif($tool->current_quantity < 0)
                                                            <span
                                                                class="badge bg-danger">{{ $tool->current_quantity }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-success">{{ $tool->current_quantity }}</span>
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 150px;">
                                                        @if ($tool->last_audited_at)
                                                            {{ $tool->last_audited_at->format('d/m/Y H:i') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 100px;" class="text-center">
                                                        {{-- Edit --}}
                                                        <a href="#" class="btn btn-sm btn-link text-primary"
                                                            title="Edit" data-bs-toggle="modal"
                                                            data-bs-target="#editToolModal{{ $tool->id }}">
                                                            <i class="bi bi-pencil-square fs-5"></i>
                                                        </a>
                                                        {{-- Delete --}}
                                                        <form action="{{ route('tool.destroy', $tool->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus tool ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-link text-danger"
                                                                title="Hapus">
                                                                <i class="bi bi-trash3-fill fs-5"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                {{-- Modal Edit Tool --}}
                                                <div class="modal fade" id="editToolModal{{ $tool->id }}"
                                                    tabindex="-1" aria-labelledby="modalEditToolLabel"
                                                    aria-hidden="true">
                                                    <div
                                                        class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                            <form action="{{ route('tool.update', $tool->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalEditToolLabel">
                                                                        Edit Tool
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body"
                                                                    style="max-height: 70vh; overflow-y: auto;">
                                                                    {{-- Kode Tool --}}
                                                                    <div class="mb-3">
                                                                        <label for="code{{ $tool->id }}"
                                                                            class="form-label">
                                                                            Tool Code
                                                                        </label>
                                                                        <input type="text" class="form-control"
                                                                            id="code{{ $tool->id }}" name="code"
                                                                            value="{{ $tool->code }}"
                                                                            placeholder="Please fill if there are custom code">
                                                                    </div>
                                                                    {{-- Nama Tool --}}
                                                                    <div class="mb-3">
                                                                        <label for="name{{ $tool->id }}"
                                                                            class="form-label">
                                                                            Tool Name*
                                                                        </label>
                                                                        <input type="text" class="form-control"
                                                                            id="name{{ $tool->id }}" name="name"
                                                                            value="{{ $tool->name }}" required>
                                                                    </div>
                                                                    {{-- Deskripsi --}}
                                                                    <div class="mb-3">
                                                                        <label for="description{{ $tool->id }}"
                                                                            class="form-label">
                                                                            Description*
                                                                        </label>
                                                                        <textarea class="form-control" id="description{{ $tool->id }}" name="description" rows="2">{{ $tool->description }}</textarea>
                                                                    </div>
                                                                    {{-- Spesifikasi --}}
                                                                    <div class="mb-3">
                                                                        <label for="spec{{ $tool->id }}"
                                                                            class="form-label">
                                                                            Spec
                                                                        </label>
                                                                        <input type="text" class="form-control"
                                                                            id="spec{{ $tool->id }}" name="spec"
                                                                            value="{{ $tool->spec }}"
                                                                            placeholder="Please fill tools specification">
                                                                    </div>
                                                                    {{-- Gambar --}}
                                                                    <div class="mb-3">
                                                                        <label for="image{{ $tool->id }}"
                                                                            class="form-label">
                                                                            Tool Image
                                                                        </label>
                                                                        <input type="file" class="form-control"
                                                                            id="image{{ $tool->id }}" name="image"
                                                                            accept="image/*">
                                                                        @if ($tool->image)
                                                                            <div class="mt-2">
                                                                                <p class="small text-muted">Current Image:
                                                                                </p>
                                                                                <img src="{{ asset('storage/' . $tool->image) }}"
                                                                                    alt="Current Image"
                                                                                    class="rounded border"
                                                                                    style="max-height: 100px;">
                                                                            </div>
                                                                        @endif
                                                                        <img id="preview-image{{ $tool->id }}"
                                                                            src="#" alt="Preview Gambar Baru"
                                                                            class="mt-3 rounded"
                                                                            style="max-height: 100px; display: none;">
                                                                    </div>
                                                                    {{-- Quantity --}}
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="quantity{{ $tool->id }}"
                                                                                class="form-label">
                                                                                Quantity*
                                                                            </label>
                                                                            <input type="number" class="form-control"
                                                                                id="quantity{{ $tool->id }}"
                                                                                name="quantity"
                                                                                value="{{ $tool->quantity }}"
                                                                                min="0" required>
                                                                        </div>

                                                                    </div>
                                                                    {{-- Locator --}}
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="locator{{ $tool->id }}"
                                                                                class="form-label">
                                                                                Locator*
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                id="locator{{ $tool->id }}"
                                                                                name="locator"
                                                                                value="{{ $tool->locator }}"
                                                                                placeholder="Ex: A.1.1.0" required>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success">
                                                                        Simpan
                                                                    </button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">
                                                                        Batal
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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

        {{-- Modal Tambah Tool --}}
        <div class="modal fade" id="addToolModal" tabindex="-1" aria-labelledby="modalTambahToolLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <form action="{{ route('tool.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahToolLabel">
                                Tambah Tool
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            {{-- Kode Tool --}}
                            <div class="mb-3">
                                <label for="code" class="form-label">Tool Code</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    value="{{ old('code') }}" placeholder="Please fill if there are custom code">
                            </div>
                            {{-- Nama Tool --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Tool Name*</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" required>
                            </div>
                            {{-- Deskripsi --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Description*</label>
                                <textarea class="form-control" id="description" name="description" rows="2" required>{{ old('description') }}</textarea>
                            </div>
                            {{-- Spesifikasi --}}
                            <div class="mb-3">
                                <label for="spec" class="form-label">Spec</label>
                                <input type="text" class="form-control" id="spec" name="spec"
                                    value="{{ old('spec') }}" placeholder="Please fill tools specification">
                            </div>
                            {{-- Gambar --}}
                            <div class="mb-3">
                                <label for="image" class="form-label">Tool Image</label>
                                <input type="file" class="form-control" id="image" name="image"
                                    accept="image/*">
                                <img id="preview-image" src="#" alt="Preview Gambar" class="mt-3 rounded"
                                    style="display: none; max-height: 100px;">
                            </div>
                            {{-- Quantity --}}
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity*</label>
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                    value="{{ old('quantity', 1) }}" min="0" required>
                            </div>
                            {{-- Locator --}}
                            <div class="mb-3">
                                <label for="locator" class="form-label">Locator*</label>
                                <input type="text" class="form-control" id="locator" name="locator"
                                    value="{{ old('locator') }}" required placeholder="Ex: A.1.1.0">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    {{-- Skrip Preview & Filter --}}
    <script>
        // Preview image add
        document.getElementById('image').addEventListener('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('preview-image');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });

        // Preview image edit per tool
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($tools as $tool)
                const input{{ $tool->id }} = document.getElementById('image{{ $tool->id }}');
                const preview{{ $tool->id }} = document.getElementById('preview-image{{ $tool->id }}');
                if (input{{ $tool->id }}) {
                    input{{ $tool->id }}.addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = e => {
                                preview{{ $tool->id }}.src = e.target.result;
                                preview{{ $tool->id }}.style.display = 'block';
                            };
                            reader.readAsDataURL(file);
                        } else {
                            preview{{ $tool->id }}.style.display = 'none';
                        }
                    });
                }
            @endforeach
        });

        const searchInput = document.getElementById('searchTool');
        const tableRows = document.querySelectorAll('#toolTableBody tr');
        const noResultRow = document.getElementById('noResultRow');

        function filterTools() {
            const searchValue = searchInput.value.toLowerCase();
            let visibleCount = 0;

            tableRows.forEach(row => {
                if (row.id === 'noResultRow') return;

                const nama = row.cells[3].textContent.toLowerCase();
                const kode = row.cells[2].textContent.toLowerCase();
                const desc = row.cells[4].textContent.toLowerCase();

                const matchSearch = nama.includes(searchValue) || kode.includes(searchValue) || desc.includes(
                    searchValue);

                row.style.display = matchSearch ? '' : 'none';
                if (matchSearch) visibleCount++;
            });

            noResultRow.style.display = visibleCount === 0 ? '' : 'none';
        }

        searchInput.addEventListener('input', filterTools);
        document.getElementById('searchForm').addEventListener('submit', e => {
            e.preventDefault();
            filterTools();
        });
    </script>
@endsection
