<nav class="main-header navbar navbar-expand bg-body navbar-light fixed-top border-bottom app-header">
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link d-md-block d-lg-block d-xl-block" href="#" role="button"
                    data-lte-toggle="sidebar">
                    <i class="bi bi-list fs-4"></i>
                </a>
            </li>
        </ul>
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            @auth
                @php $role = auth()->user()->role; @endphp


                <!-- User menu -->
                {{-- <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-avatar.png') }}"
                            class="rounded-circle shadow" alt="User Image"
                            style="width: 40px; height: 40px; object-fit: cover;" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header bg-primary text-white text-center p-3">
                            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-avatar.png') }}"
                                class="rounded-circle shadow mb-2" alt="User Image"
                                style="width: 80px; height: 80px; object-fit: cover;" />
                            <p class="mb-0">
                                {{ Auth::user()->name }}<br>
                                <small>{{ Auth::user()->role->name ?? 'User' }}</small><br>
                                <small>Dibuat {{ Auth::user()->created_at->format('M Y') }}</small>
                            </p>
                        </li>
                        <li
                            class="user-footer d-flex justify-content-between align-items-center gap-2 px-3 py-2 border-top">
                            <div class="d-flex flex-column gap-2 ">
                                <a href="#"
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 justify-content-center"
                                    data-bs-toggle="modal" data-bs-target="#profileModal">
                                    <i class="bi bi-person-circle"></i> Profile
                                </a>
                                <div class="d-flex flex-row gap-2">

                                    <!-- Tombol untuk ganti password -->
                                    <a href="#" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2"
                                        data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="bi bi-key"></i> Ganti Password
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm d-flex align-items-center gap-2"
                                        id="logout-btn">
                                        <i class="bi bi-box-arrow-right"></i> Sign out
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li> --}}
            @else
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                </li>
            @endauth
        </ul>
        <!--end::End Navbar Links-->
    </div>
</nav>
<!--end::Header-->

<!-- Spacer untuk hindari overlap konten -->
<div class="pt-5"></div>




{{-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Foto profil preview


            // Logout SweetAlert
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin keluar?',
                        text: "Kamu akan keluar dari dashboard ini.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, logout!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logout-form').submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush --}}
