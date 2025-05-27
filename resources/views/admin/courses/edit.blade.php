{{-- resources/views/admin/courses/edit.blade.php --}}
@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Kursus: {{ $course->title }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
                            <strong class="font-bold">Oops! Ada beberapa masalah:</strong>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.courses.update', $course->id) }}" enctype="multipart/form-data"> {{-- Penting: enctype untuk file upload --}}
                        @csrf
                        @method('PUT') {{-- Method spoofing untuk update --}}

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Kursus <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Instruktur <span class="text-red-500">*</span></label>
                            <select name="user_id" id="user_id" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Pilih Instruktur</option>
                                @forelse ($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('user_id', $course->user_id) == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada instruktur tersedia</option>
                                @endforelse
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $course->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="learning_objectives" class="block text-sm font-medium text-gray-700">Tujuan Pembelajaran</label>
                            <textarea name="learning_objectives" id="learning_objectives" rows="4"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('learning_objectives', $course->learning_objectives) }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $course->price) }}" step="0.01" min="0"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach ($course_statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $course->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Gambar Sampul Saat Ini</label>
                            @if($course->cover_image_path)
                                <img src="{{ Storage::url($course->cover_image_path) }}" alt="Cover Image {{ $course->title }}" class="mt-1 w-48 h-auto object-cover rounded">
                            @else
                                <p class="mt-1 text-sm text-gray-500">Tidak ada gambar sampul.</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="cover_image" class="block text-sm font-medium text-gray-700">Ganti Gambar Sampul (Opsional)</label>
                            <input type="file" name="cover_image" id="cover_image"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti gambar.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.courses.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline mr-4">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Script JavaScript tambahan bisa diletakkan di sini jika diperlukan
</script>
@endpush
