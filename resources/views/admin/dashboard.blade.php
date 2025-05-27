{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin') {{-- Menggunakan layout yang sudah kita siapkan --}}

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Admin Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-6"> {{-- Anda bisa mengatur padding di sini atau di layout --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Selamat datang di Dashboard Admin!") }}

                    <div class="mt-4">
                        <p>Total Pengguna Terdaftar: {{ $totalUsers }}</p>
                        {{-- <p>Total Kursus: {{ $totalCourses }}</p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection