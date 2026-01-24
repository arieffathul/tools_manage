<?php if (!isset($currentPage)) {
    $currentPage = '';
} ?>
<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="#" class="brand-link d-flex align-items-center">
            <i class="bi bi-tools me-2 text-white" style="font-size: 1.4rem;"></i>
            <span class="brand-text fw-semibold text-white">Tools Management</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ $currentPage === 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart-line-fill"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ '#' }}"
                        class="nav-link {{ in_array($currentPage, ['gedung', 'lantai', 'ruangan']) ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-badge-fill"></i>
                        <p>Engineer</p>
                    </a>
                </li>


                <li class="nav-item">
                    {{-- <a href="{{ route('admin.request.index') }}" --}}
                    <a href="{{ '#' }}"
                        class="nav-link {{ $currentPage === 'request-booking' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-nut-fill"></i>
                        <p>Tools</p>
                    </a>
                </li>
                <li class="nav-item">
                    {{-- <a href="{{ route('admin.request.index') }}" --}}
                    <a href="{{ '#' }}"
                        class="nav-link {{ $currentPage === 'request-booking' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-bar-graph-fill"></i>
                        <p>Borrows Data</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<!--end::Sidebar-->
