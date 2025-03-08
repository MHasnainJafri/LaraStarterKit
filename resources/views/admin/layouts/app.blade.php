<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

    {{-- Dynamic SEO Tags --}}
    <title>@yield('title', 'Admin Panel | ' . config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Admin Dashboard for managing the platform.')">
    <meta name="keywords" content="@yield('meta_keywords', 'admin, dashboard, management')">
    <meta name="author" content="Muhammad Hasnain Jafri">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('admin/images/favicon.ico') }}" type="image/x-icon">

    {{-- Styles --}}
    @stack('styles') {{-- Additional styles per page --}}

    {{-- Include additional styles if needed --}}
    @include('admin.layouts.styles')

</head>
<body>
    <div class="page">

    {{-- Sidebar --}}
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

    <div class="page-wrapper">
      
            @yield('content')
       

        {{-- Footer --}}
        @include('admin.layouts.footer')
    </div>
@stack('modals')

    {{-- Scripts --}}
     
    @include('admin.layouts.scripts')
@stack('scripts')
</body>
</html>
