@extends('layouts.app')
@php
    $currentPage = 'engineers';
@endphp

@section('title', 'Engineer | Tools Management')
@section('content')
    <main class="app-main">
        <!-- Header -->
        <div class="app-content-header py-4 mb-4 bg-white border-bottom shadow-sm animate-fade-in">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <h1 class="mb-0">Kelola Engineer</h1>
                    </div>
                </div>

                <div class="row justify-content-between align-items-center">
                    <div class="col-auto mb-3">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('engineer.index') }}"
                                class="btn {{ !$viewInactive ? 'btn-success' : 'btn-outline-success' }}">
                                Active
                            </a>
                            <a href="{{ route('engineer.index', ['status' => 'inactive']) }}"
                                class="btn {{ $viewInactive ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                Inactive
                            </a>
                        </div>
                    </div>
                    <div class="col-auto mb-3">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEngineerModal">
                            <i class="bi bi-plus-lg"></i> Tambah Engineer
                        </button>
                    </div>
                </div>

                <div class="row justify-content-between align-items-end">
                    <div class="col-auto d-flex flex-wrap align-items-end gap-3 mb-3">
                        <form action="{{ route('engineer.index') }}" method="GET" id="filterForm">
                            @if ($viewInactive)
                                <input type="hidden" name="status" value="inactive">
                            @endif

                            <div class="d-flex flex-wrap gap-3 align-items-end">
                                <div>
                                    <label class="form-label mb-1 small">Shift</label>
                                    <select class="form-select form-select-sm" name="shift" style="min-width: 130px;">
                                        <option value="">Semua Shift</option>
                                        <option value="day" {{ request('shift') == 'day' ? 'selected' : '' }}>Day
                                        </option>
                                        <option value="night" {{ request('shift') == 'night' ? 'selected' : '' }}>Night
                                        </option>
                                        <option value="flexible" {{ request('shift') == 'flexible' ? 'selected' : '' }}>
                                            Flexible</option>
                                        <option value="weekend" {{ request('shift') == 'weekend' ? 'selected' : '' }}>
                                            Weekend</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label mb-1 small">Cari Engineer</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" name="search"
                                            placeholder="Nama Engineer" value="{{ request('search') }}">
                                        {{-- <button class="btn btn-success" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button> --}}
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-auto mb-3">
                        <label class="form-label mb-1 small">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" form="filterForm" class="btn btn-sm btn-primary" title="Filter">
                                <i class="bi bi-funnel"></i> Cari
                            </button>

                            <a href="{{ route('engineer.index', $viewInactive ? ['status' => 'inactive'] : []) }}"
                                class="btn btn-sm btn-outline-danger" title="Reset">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        @if ($engineers->isEmpty())
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 55vh">
                                <div class="text-center text-muted">
                                    <h4>Tidak Ada Data Engineer {{ $viewInactive ? 'Inactive' : 'Active' }}</h4>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Shift</th>
                                            <th>Status</th>
                                            @if ($viewInactive)
                                                <th>Inactivated At</th>
                                            @endif
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="engineerTableBody">
                                        @foreach ($engineers as $engineer)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $engineer->name }}</td>
                                                <td>{{ ucfirst($engineer->shift) }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $engineer->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($engineer->status) }}
                                                    </span>
                                                </td>
                                                @if ($viewInactive)
                                                    <td>{{ $engineer->inactived_at ?? '-' }}</td>
                                                @endif
                                                <td class="text-center">
                                                    {{-- Edit --}}
                                                    <a href="#" class="btn btn-sm btn-link text-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editEngineerModal{{ $engineer->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    {{-- Show different actions based on status --}}
                                                    @if ($engineer->status === 'active')
                                                        <!-- Inactive Button -->
                                                        <form action="{{ route('engineer.inactive', $engineer->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-sm btn-link text-warning">
                                                                <i class="bi bi-person-x-fill"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <!-- Activate Button -->
                                                        <form action="{{ route('engineer.activate', $engineer->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-sm btn-link text-success">
                                                                <i class="bi bi-person-check-fill"></i>
                                                            </button>
                                                        </form>

                                                        <!-- Delete Button -->
                                                        <form action="{{ route('engineer.destroy', $engineer->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus engineer ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-link text-danger">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>

                                            {{-- Modal Edit Engineer --}}
                                            <div class="modal fade" id="editEngineerModal{{ $engineer->id }}"
                                                tabindex="-1" aria-labelledby="modalEditEngineerLabel{{ $engineer->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <form action="{{ route('engineer.update', $engineer->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="modalEditEngineerLabel{{ $engineer->id }}">
                                                                    Edit Engineer
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body"
                                                                style="max-height: 70vh; overflow-y: auto;">

                                                                {{-- Nama Engineer --}}
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama Engineer</label>
                                                                    <input type="text" class="form-control"
                                                                        name="name" value="{{ $engineer->name }}"
                                                                        required>
                                                                </div>

                                                                {{-- Shift --}}
                                                                <div class="mb-3">
                                                                    <label class="form-label">Shift</label>
                                                                    <select class="form-select" name="shift" required>
                                                                        <option value="day"
                                                                            {{ $engineer->shift === 'day' ? 'selected' : '' }}>
                                                                            Day
                                                                        </option>
                                                                        <option value="night"
                                                                            {{ $engineer->shift === 'night' ? 'selected' : '' }}>
                                                                            Night
                                                                        </option>
                                                                        <option value="flexible"
                                                                            {{ $engineer->shift === 'flexible' ? 'selected' : '' }}>
                                                                            Flexible
                                                                        </option>
                                                                        <option value="weekend"
                                                                            {{ $engineer->shift === 'weekend' ? 'selected' : '' }}>
                                                                            Weekend
                                                                        </option>
                                                                    </select>
                                                                </div>

                                                                {{-- Status --}}
                                                                <div class="mb-3">
                                                                    <label class="form-label">Status</label>
                                                                    <select class="form-select" name="status" required>
                                                                        <option value="active"
                                                                            {{ $engineer->status === 'active' ? 'selected' : '' }}>
                                                                            Active
                                                                        </option>
                                                                        <option value="inactive"
                                                                            {{ $engineer->status === 'inactive' ? 'selected' : '' }}>
                                                                            Inactive
                                                                        </option>
                                                                    </select>
                                                                </div>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">
                                                                    Simpan Perubahan
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
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <em>Tidak ada Engineer yang cocok dengan pencarian.</em>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Tambah Engineer -->
        <div class="modal fade" id="addEngineerModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('engineer.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Engineer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Shift</label>
                                <select class="form-select" name="shift" required>
                                    <option value="day">Day</option>
                                    <option value="night">Night</option>
                                    <option value="flexible">Flexible</option>
                                    <option value="weekend">Weekend</option>
                                </select>
                            </div>

                            <input type="hidden" name="status" value="active">
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success">Simpan</button>
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script></script>
@endsection
