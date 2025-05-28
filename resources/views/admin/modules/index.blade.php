{{-- resources/views/admin/modules/index.blade.php --}}
@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Modul untuk Kursus: {{ $course->title }}
    </h2>
@endsection

@section('content')
    <div class="container mx-auto">
        <div class="mb-4">
            {{-- <a href="{{ route('admin.courses.show', $course->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">&larr; Kembali ke Detail Kursus</a> --}}
            <a href="{{ route('admin.courses.index') }}" class="text-indigo-600 hover:text-indigo-900 mr-4">&larr; Kembali ke Detail Kursus</a>
            <a href="{{ route('admin.courses.modules.create', $course->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Tambah Modul Baru
            </a>
        </div>

        @include('partials.flash-messages')

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Urutan</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul Modul</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($modules as $module)
                        <tr>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $module->order }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                {{ $module->title }}
                                <a href="{{ route('admin.courses.modules.lessons.index', ['course' => $course->id, 'module' => $module->id]) }}" class="text-blue-500 hover:text-blue-700 block text-xs">
                                    Lihat Pelajaran ({{ $module->lessons()->count() }}) {{-- Tampilkan jumlah pelajaran --}}
                                </a>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ Str::limit($module->description, 70) }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ route('admin.courses.modules.edit', ['course' => $course->id, 'module' => $module->id]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form action="{{ route('admin.courses.modules.destroy', ['course' => $course->id, 'module' => $module->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus modul ini?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">Belum ada modul untuk kursus ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if(isset($modules) && $modules->count() > 0 && method_exists($modules, 'links'))
            <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $modules->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection