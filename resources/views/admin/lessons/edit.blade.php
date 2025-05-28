{{-- resources/views/admin/lessons/create.blade.php --}}
@extends('layouts.admin') {{-- INI HARUS DI BARIS PALING PERTAMA --}}

@section('header') {{-- Konten untuk @yield('header') di layout --}}
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tambah Pelajaran Baru ke Modul: {{ $module->title }} 
        <span class="text-base font-normal text-gray-500">(Kursus: {{ $course->title }})</span>
    </h2>
@endsection

@section('content') {{-- SEMUA KONTEN UTAMA (FORM, DLL) HARUS DI DALAM SECTION INI --}}
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    {{-- Notifikasi error validasi --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-400 rounded-md">
                             <strong class="font-bold">Oops! Ada beberapa masalah dengan input Anda:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM MULAI DI SINI --}}
                    <form method="POST" action="{{ route('admin.courses.modules.lessons.update', ['course' => $course->id, 'module' => $module->id, 'lesson' => $lesson->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Pelajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-6">
                            <label for="content_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Konten <span class="text-red-500">*</span></label>
                            <select name="content_type" id="content_type" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @php
                                    // Variabel $content_types seharusnya di-pass dari controller create method
                                    $content_types_options = $content_types ?? [
                                        'text' => 'Teks / Markdown',
                                        'video_embed' => 'Embed Video (YouTube/Vimeo URL)',
                                        'image_url' => 'URL Gambar Eksternal',
                                        'pdf_url' => 'URL PDF Eksternal',
                                        'file_upload' => 'Unggah File (PDF, PPT, DOC, Gambar)',
                                    ];
                                @endphp
                                @foreach ($content_types_options as $value => $label)
                                    <option value="{{ $value }}" {{ old('content_type', 'text') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="content_url_or_text_field_container" class="mb-6">
                            <label for="content_url_or_text" class="block text-sm font-medium text-gray-700 mb-1" id="content_url_or_text_label">Konten/URL</label>
                            <textarea name="content_url_or_text" id="content_url_or_text" rows="8"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('content_url_or_text') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500" id="content_helper_text">Masukkan teks, URL video (YouTube/Vimeo), URL gambar, atau URL PDF.</p>
                        </div>
                        
                        <div id="uploaded_file_field_container" class="mb-6" style="display: none;"> {{-- Sembunyikan awalnya --}}
                            <label for="uploaded_file" class="block text-sm font-medium text-gray-700 mb-1">Ganti File (Opsional)</label>
                             @if($lesson->content_type == 'file_upload' && $lesson->content_url_or_text)
                                <p class="text-sm text-gray-600 my-2">
                                    File saat ini: 
                                    <a href="{{ Storage::url($lesson->content_url_or_text) }}" target="_blank" class="text-indigo-600 hover:underline">
                                        {{ basename($lesson->content_url_or_text) }}
                                    </a>
                                </p>
                            @endif
                            <input type="file" name="uploaded_file" id="uploaded_file"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 cursor-pointer">
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti file. Maks: 10MB (PDF, PPT, DOC, Gambar).</p>
                        </div>

                        <div class="mb-6">
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">Estimasi Durasi (Menit, Opsional)</label>
                            <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes') }}" min="0"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-6">
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Urutan (Opsional, angka)</label>
                            <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-xs text-gray-500 mt-1">Jika dikosongkan atau 0, akan diatur otomatis sebagai pelajaran terakhir.</p>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="hidden" name="is_previewable" value="0"> <input type="checkbox" name="is_previewable" id="is_previewable" value="1" {{ old('is_previewable') ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="is_previewable" class="ml-2 block text-sm text-gray-900">
                                    Dapat dilihat sebagai pratinjau (preview) oleh pengguna yang belum enroll?
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.courses.modules.lessons.index', ['course' => $course->id, 'module' => $module->id]) }}" class="text-sm text-gray-600 hover:text-gray-900 underline mr-4">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Perubahan Pelajaran
                            </button>
                        </div>
                    </form>
                    {{-- FORM SELESAI DI SINI --}}
                </div>
            </div>
        </div>
    </div>
@endsection {{-- AKHIR DARI SECTION CONTENT --}}

@push('scripts') {{-- Section untuk JavaScript jika ada --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const contentTypeSelect = document.getElementById('content_type');
        const contentUrlOrTextFieldContainer = document.getElementById('content_url_or_text_field_container');
        const uploadedFileFieldContainer = document.getElementById('uploaded_file_field_container');
        const contentUrlOrTextLabel = document.getElementById('content_url_or_text_label');
        const contentHelperText = document.getElementById('content_helper_text');

        function toggleFields() {
            const selectedType = contentTypeSelect.value;

            if (selectedType === 'file_upload') {
                contentUrlOrTextFieldContainer.style.display = 'none';
                uploadedFileFieldContainer.style.display = 'block';
            } else {
                contentUrlOrTextFieldContainer.style.display = 'block';
                uploadedFileFieldContainer.style.display = 'none';
                
                if (selectedType === 'text') {
                    contentUrlOrTextLabel.textContent = 'Konten Teks / Markdown';
                    contentHelperText.textContent = 'Masukkan konten teks atau format Markdown di sini.';
                } else if (selectedType === 'video_embed') {
                    contentUrlOrTextLabel.textContent = 'URL Embed Video';
                    contentHelperText.textContent = 'Masukkan URL embed video (misal: dari YouTube atau Vimeo). Contoh: https://www.youtube.com/embed/VIDEO_ID';
                } else if (selectedType === 'image_url') {
                    contentUrlOrTextLabel.textContent = 'URL Gambar Eksternal';
                    contentHelperText.textContent = 'Masukkan URL lengkap ke gambar eksternal (misal: https://example.com/image.jpg).';
                } else if (selectedType === 'pdf_url') {
                    contentUrlOrTextLabel.textContent = 'URL PDF Eksternal';
                    contentHelperText.textContent = 'Masukkan URL lengkap ke file PDF eksternal.';
                } else {
                    contentUrlOrTextLabel.textContent = 'Konten/URL';
                    contentHelperText.textContent = 'Masukkan konten atau URL yang sesuai.';
                }
            }
        }

        contentTypeSelect.addEventListener('change', toggleFields);
        toggleFields(); // Panggil saat halaman dimuat untuk mengatur tampilan awal field
    });
</script>
@endpush