{{-- resources/views/admin/courses/index.blade.php --}}
@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Kursus') }}
    </h2>
@endsection

@section('content')
    <div class="container mx-auto">
        <div class="mb-4 text-right">
            <a href="{{ route('admin.courses.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Tambah Kursus Baru
            </a>
        </div>

        @include('partials.flash-messages') {{-- Asumsi Anda punya partial untuk flash messages --}}
        {{-- Atau tampilkan manual: --}}
        {{-- @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif --}}


        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gambar</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Instruktur</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                @if($course->cover_image_path)
                                    <img src="{{ Storage::url($course->cover_image_path) }}" alt="{{ $course->title }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <span class="text-xs text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $course->title }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $course->category->name ?? 'N/A' }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $course->instructor->name ?? 'N/A' }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($course->status == 'published') bg-green-100 text-green-800 @endif
                                    @if($course->status == 'draft') bg-yellow-100 text-yellow-800 @endif
                                    @if($course->status == 'archived') bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ route('admin.courses.modules.index', $course->id) }}" class="text-green-600 hover:text-green-900 mr-3 text-xs">
                                    Modul ({{ $course->modules()->count() }}) {{-- Tampilkan jumlah modul --}}
                                </a>
                                <a href="{{ route('admin.courses.edit', $course->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 text-xs">Edit</a>
                                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">Tidak ada kursus ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
             @if(isset($courses) && $courses->count() > 0)
            <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $courses->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection