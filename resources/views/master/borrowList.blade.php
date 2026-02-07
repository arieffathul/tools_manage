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
                        {{-- Search --}}
                        {{-- <div>
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
                        </div> --}}
                    </div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        @if ($borrows->isEmpty())
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 55vh">
                                <div class="text-center text-muted">
                                    <h4>Tidak Ada Data Peminjaman</h4>
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
