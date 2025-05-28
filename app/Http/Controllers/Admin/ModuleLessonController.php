<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course; // Untuk type-hinting
use App\Models\Module; // Untuk type-hinting
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Untuk file upload
use Illuminate\Validation\Rule; // Untuk validasi

class ModuleLessonController extends Controller
{
    /**
     * Display a listing of the lessons for a specific module.
     */
    public function index(Course $course, Module $module)
    {
        // Pastikan modul milik course yang benar (opsional, tapi baik untuk integritas)
        if ($module->course_id !== $course->id) {
            abort(404, 'Module not found for this course.');
        }
        $lessons = $module->lessons()->orderBy('order')->paginate(10);
        return view('admin.lessons.index', compact('course', 'module', 'lessons'));
    }

    /**
     * Show the form for creating a new lesson for a specific module.
     */
    public function create(Course $course, Module $module)
    {
        if ($module->course_id !== $course->id) {
            abort(404, 'Module not found for this course.');
        }
        $content_types = [ // Definisikan tipe konten yang didukung
            'text' => 'Teks / Markdown',
            'video_embed' => 'Embed Video (YouTube/Vimeo URL)',
            'image_url' => 'URL Gambar Eksternal',
            'pdf_url' => 'URL PDF Eksternal',
            'file_upload' => 'Unggah File (PDF, PPT, DOC, Gambar)',
        ];
        return view('admin.lessons.create', compact('course', 'module', 'content_types'));
    }

    /**
     * Store a newly created lesson in storage.
     */
    public function store(Request $request, Course $course, Module $module)
    {
        if ($module->course_id !== $course->id) {
            abort(404, 'Module not found for this course.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:text,video_embed,image_url,pdf_url,file_upload',
            'content_url_or_text' => Rule::requiredIf(function () use ($request) {
                return !in_array($request->content_type, ['file_upload']); // Wajib jika bukan file_upload
            }),
            'uploaded_file' => Rule::requiredIf($request->content_type === 'file_upload') . '|nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,gif|max:10240', // Max 10MB
            'duration_minutes' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_previewable' => 'nullable|boolean',
        ]);

        $data = $request->only(['title', 'content_type', 'duration_minutes', 'order', 'is_previewable']);
        $data['module_id'] = $module->id;
        $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        $data['order'] = $request->filled('order') ? $request->order : ($module->lessons()->max('order') + 1);
        $data['is_previewable'] = $request->boolean('is_previewable');


        if ($request->content_type === 'file_upload' && $request->hasFile('uploaded_file')) {
            $fileName = time() . '_' . $request->file('uploaded_file')->getClientOriginalName();
            $path = $request->file('uploaded_file')->storeAs('lesson_files/' . $module->id, $fileName, 'public');
            $data['content_url_or_text'] = $path;
        } else {
            $data['content_url_or_text'] = $request->content_url_or_text;
        }
        
        Lesson::create($data);

        return redirect()->route('admin.courses.modules.lessons.index', ['course' => $course->id, 'module' => $module->id])
                         ->with('success', 'Pelajaran berhasil ditambahkan ke modul.');
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit(Course $course, Module $module, Lesson $lesson)
    {
        if ($module->course_id !== $course->id || $lesson->module_id !== $module->id) {
            abort(404, 'Resource not found or mismatched.');
        }
        $content_types = [
            'text' => 'Teks / Markdown',
            'video_embed' => 'Embed Video (YouTube/Vimeo URL)',
            'image_url' => 'URL Gambar Eksternal',
            'pdf_url' => 'URL PDF Eksternal',
            'file_upload' => 'Unggah File (PDF, PPT, DOC, Gambar)',
        ];
        return view('admin.lessons.edit', compact('course', 'module', 'lesson', 'content_types'));
    }

    /**
     * Update the specified lesson in storage.
     */
    public function update(Request $request, Course $course, Module $module, Lesson $lesson)
    {
        if ($module->course_id !== $course->id || $lesson->module_id !== $module->id) {
            abort(404, 'Resource not found or mismatched.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:text,video_embed,image_url,pdf_url,file_upload',
            'content_url_or_text' => Rule::requiredIf(function () use ($request, $lesson) { // << TAMBAHKAN $lesson DI SINI
                // Wajib jika bukan file_upload ATAU jika file_upload tapi tidak ada file baru yang diunggah DAN tidak ada file lama
                return $request->content_type !== 'file_upload' || 
                       ($request->content_type === 'file_upload' && !$request->hasFile('uploaded_file') && !$lesson->content_url_or_text);
            }),
            'uploaded_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,gif|max:10240', // Max 10MB
            'duration_minutes' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_previewable' => 'nullable|boolean',
        ]);

        $data = $request->only(['title', 'content_type', 'duration_minutes', 'order', 'is_previewable']);
        
        // Hanya buat slug baru jika judul berubah atau slug belum ada
        if (!$lesson->slug || $request->title !== $lesson->title) {
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }
        
        if ($request->filled('order')) {
            $data['order'] = $request->order;
        }
        $data['is_previewable'] = $request->boolean('is_previewable');

        if ($request->content_type === 'file_upload') {
            if ($request->hasFile('uploaded_file')) {
                // Hapus file lama jika ada dan tipenya file_upload
                if ($lesson->content_type === 'file_upload' && $lesson->content_url_or_text && Storage::disk('public')->exists($lesson->content_url_or_text)) {
                    Storage::disk('public')->delete($lesson->content_url_or_text);
                }
                // Simpan file baru
                $fileName = time() . '_' . $request->file('uploaded_file')->getClientOriginalName();
                $path = $request->file('uploaded_file')->storeAs('lesson_files/' . $module->id, $fileName, 'public');
                $data['content_url_or_text'] = $path;
            } else {
                // Jika tipe konten TIDAK diubah menjadi file_upload, dan sebelumnya adalah file_upload, hapus file lama.
                // Ini terjadi jika user mengubah tipe dari file_upload ke text/url dll, tanpa mengunggah file baru.
                if ($lesson->content_type === 'file_upload' && $request->content_type !== 'file_upload' && $lesson->content_url_or_text && Storage::disk('public')->exists($lesson->content_url_or_text)) {
                    Storage::disk('public')->delete($lesson->content_url_or_text);
                    // Setelah file dihapus, content_url_or_text harus diisi dari input textarea
                    $data['content_url_or_text'] = $request->content_url_or_text;
                }
                // Jika tipe konten tetap file_upload dan tidak ada file baru, JANGAN ubah $data['content_url_or_text']
                // Biarkan menggunakan nilai yang sudah ada di $lesson->content_url_or_text
                // kecuali jika tipe konten diubah dari file_upload ke tipe lain (sudah ditangani di atas)
                else if ($request->content_type !== 'file_upload') {
                     $data['content_url_or_text'] = $request->content_url_or_text;
                }
            }
        } else { // Jika tipe konten yang diminta BUKAN file_upload
            // Jika tipe konten sebelumnya adalah file_upload, hapus file lama
            if ($lesson->content_type === 'file_upload' && $lesson->content_url_or_text && Storage::disk('public')->exists($lesson->content_url_or_text)) {
                Storage::disk('public')->delete($lesson->content_url_or_text);
            }
            $data['content_url_or_text'] = $request->content_url_or_text;
        }

        $lesson->update($data);

        return redirect()->route('admin.courses.modules.lessons.index', ['course' => $course->id, 'module' => $module->id])
                         ->with('success', 'Pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified lesson from storage.
     */
    public function destroy(Course $course, Module $module, Lesson $lesson)
    {
        if ($module->course_id !== $course->id || $lesson->module_id !== $module->id) {
            abort(404, 'Resource not found or mismatched.');
        }

        // Hapus file dari storage jika tipenya file_upload dan ada path filenya
        if ($lesson->content_type === 'file_upload' && $lesson->content_url_or_text && Storage::disk('public')->exists($lesson->content_url_or_text)) {
            Storage::disk('public')->delete($lesson->content_url_or_text);
        }

        $lesson->delete();

        return redirect()->route('admin.courses.modules.lessons.index', ['course' => $course->id, 'module' => $module->id])
                         ->with('success', 'Pelajaran berhasil dihapus.');
    }
}
