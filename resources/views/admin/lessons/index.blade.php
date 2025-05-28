{{-- resources/views/admin/lessons/index.blade.php --}}
@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Pelajaran untuk Modul: {{ $module->title }} <span class="text-base font-normal text-gray-500">(Kursus: {{ $course->title }})</span>
    </h2>
@endsection

@section('content')
    <div class="container mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.courses.modules.index', $course->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">&larr; Kembali ke Daftar Modul</a>
            <a href="{{ route('admin.courses.modules.lessons.create', ['course' => $course->id, 'module' => $module->id]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Pelajaran Baru
            </a>
        </div>

        @include('partials.flash-messages')

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Urutan</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul Pelajaran</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe Konten</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Durasi (Menit)</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Preview?</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lessons as $lesson)
                        <tr>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $lesson->order }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $lesson->title }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ Str::title(str_replace('_', ' ', $lesson->content_type)) }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $lesson->duration_minutes ?? 'N/A' }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                <span class="{{ $lesson->is_previewable ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $lesson->is_previewable ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ route('admin.courses.modules.lessons.edit', ['course' => $course->id, 'module' => $module->id, 'lesson' => $lesson->id]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form action="{{ route('admin.courses.modules.lessons.destroy', ['course' => $course->id, 'module' => $module->id, 'lesson' => $lesson->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelajaran ini?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">Belum ada pelajaran untuk modul ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if(isset($lessons) && $lessons->count() > 0 && method_exists($lessons, 'links'))
            <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $lessons->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection