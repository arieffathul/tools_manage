<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title') - {{ config('app.name') }} </title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap Bundle JS (termasuk Popper) -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet" />

    {{-- untuk sweet alert --}}
    <link rel="stylesheet" href="sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
        integrity="sha512-BKSk8fFlJMNQ0sSLBzMxEL6KQZJYl+9Ajeb/1BdhU/6x6zB7D1jMvz07/Xurtj2dezf9sW5JpGFL2fFep+cTkg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Inject page-specific styles here --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<!--end::Head-->
@stack('styles')
<style>
    /* Untuk Chrome, Edge, Safari */
    .dropdown-menu::-webkit-scrollbar {
        width: 4px;
        /* Lebar scrollbar */
    }

    .dropdown-menu::-webkit-scrollbar-track {
        background: transparent;
        /* Background track transparan */
    }

    .dropdown-menu::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        /* Warna thumb transparan */
        border-radius: 3px;
        /* Sudut membulat */
    }

    /* Custom minimal untuk hasil dropdown */
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

    /* Saat input focus */
    .form-control:focus {
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        position: relative;
        z-index: 1051;
    }

    /* Custom styling untuk tombol clear */
    /* Pastikan tombol clear terlihat */
    .search-clear-btn {
        border-top-right-radius: 0.25rem !important;
        border-bottom-right-radius: 0.25rem !important;
        padding: 0.25rem 0.5rem !important;
        display: block;
    }

    /* Icon di dalam tombol */
    .search-clear-btn i {
        font-size: 1rem;
        line-height: 1;
        display: block;
    }

    .hover-shadow:hover {
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        transform: translateY(-2px);
        transition: all 0.2s;
    }

    .border-4 {
        border-width: 4px !important;
    }
</style>
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary sidebar-open">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        @include('layouts.header')
        @include('layouts.sidebar')
        @yield('content')
        @include('layouts.footer')
    </div>
    @stack('scripts') <!-- pindahkan ke sini -->
</body>
