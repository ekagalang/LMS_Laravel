<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category; // Untuk dropdown kategori
use App\Models\User; // Untuk dropdown instruktur
use App\Models\Role; // Untuk filter instruktur
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Untuk slug
use Illuminate\Support\Facades\Storage; // Untuk file upload

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with(['category', 'instructor'])->orderBy('title')->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        
        // Ambil role 'instructor'
        $instructorRole = Role::where('name', 'instructor')->first();
        $instructors = [];
        if ($instructorRole) {
            // Ambil user yang memiliki role 'instructor'
            $instructors = User::whereHas('roles', function ($query) use ($instructorRole) {
                $query->where('role_id', $instructorRole->id);
            })->orderBy('name')->get();
        } else {
            // Handle jika role instruktur tidak ditemukan (opsional, beri pesan atau log)
            // Untuk sementara, kita biarkan $instructors kosong jika role tidak ada
        }
        
        $course_statuses = ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']; // Opsi status

        return view('admin.courses.create', compact('categories', 'instructors', 'course_statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:courses,title',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id', // user_id adalah instructor_id
            'description' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // max 2MB
        ]);

        $data = $request->except('cover_image'); // Ambil semua data kecuali cover_image
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('cover_image')) {
            // Simpan gambar baru
            // Nama file unik: timestamp + nama original. Simpan di public/storage/course_covers
            $fileName = time() . '_' . $request->file('cover_image')->getClientOriginalName();
            $path = $request->file('cover_image')->storeAs('course_covers', $fileName, 'public');
            $data['cover_image_path'] = $path;
        }

        Course::create($data);

        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        // Biasanya untuk admin, lebih sering langsung ke edit
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $categories = Category::orderBy('name')->get();
        $instructorRole = Role::where('name', 'instructor')->first();
        $instructors = [];
        if ($instructorRole) {
            $instructors = User::whereHas('roles', function ($query) use ($instructorRole) {
                $query->where('role_id', $instructorRole->id);
            })->orderBy('name')->get();
        }
        $course_statuses = ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'];

        return view('admin.courses.edit', compact('course', 'categories', 'instructors', 'course_statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:courses,title,' . $course->id,
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->except('cover_image');
        if (empty($data['slug']) || $request->title !== $course->title) { // Update slug jika title berubah
            $data['slug'] = Str::slug($request->title);
        }


        if ($request->hasFile('cover_image')) {
            // Hapus gambar lama jika ada
            if ($course->cover_image_path && Storage::disk('public')->exists($course->cover_image_path)) {
                Storage::disk('public')->delete($course->cover_image_path);
            }
            // Simpan gambar baru
            $fileName = time() . '_' . $request->file('cover_image')->getClientOriginalName();
            $path = $request->file('cover_image')->storeAs('course_covers', $fileName, 'public');
            $data['cover_image_path'] = $path;
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        // Hapus gambar dari storage jika ada
        if ($course->cover_image_path && Storage::disk('public')->exists($course->cover_image_path)) {
            Storage::disk('public')->delete($course->cover_image_path);
        }

        $course->delete();
        // Tambahkan logika jika ada relasi lain yang perlu dihapus (misal modul, lesson, enrollment)
        // atau dicegah jika masih ada relasi aktif.

        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil dihapus.');
    }
}
