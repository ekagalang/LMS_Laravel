{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Atau jika Anda menggunakan CSS terpisah untuk admin --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}"> --}}
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation') {{-- Pastikan ini sesuai dengan struktur Anda --}}

        <div class="flex">
            <aside class="w-64 bg-white shadow-md hidden md:block">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-700">Admin Menu</h2>
                    <nav class="mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-500 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500 text-white' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-500 hover:text-white {{ request()->routeIs('admin.users.index') ? 'bg-blue-500 text-white' : '' }}">
                            Manajemen Pengguna
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-500 hover:text-white {{ request()->routeIs('admin.categories.index') || request()->routeIs('admin.categories.create') || request()->routeIs('admin.categories.edit') ? 'bg-blue-500 text-white' : '' }}">
                            Manajemen Kategori
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-500 hover:text-white {{ (request()->routeIs('admin.courses.index') || request()->routeIs('admin.courses.create') || request()->routeIs('admin.courses.edit')) ? 'bg-blue-500 text-white' : '' }}">
                            Manajemen Kursus
                        </a>
                        {{-- Tambahkan link menu admin lainnya di sini --}}
                    </nav>
                </div>
            </aside>

            <main class="flex-1">
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @yield('header') {{-- Ganti dari {{ $header }} menjadi @yield('header') --}}
                    </div>
                </header>

                <div class="p-6"> {{-- Menambahkan padding di sini atau di dalam section di view anak --}}
                    @yield('content') {{-- Ganti dari {{ $slot }} menjadi @yield('content') --}}
                </div>
            </main>
        </div>
    </div>
</body>
</html>