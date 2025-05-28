<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course; // Penting untuk menerima objek Course
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseModuleController extends Controller
{
    /**
     * Display a listing of the modules for a specific course.
     */
    public function index(Course $course) // Terima objek $course dari route model binding
    {
        // Ambil modul yang berelasi dengan $course, sudah diurutkan berdasarkan 'order' dari relasi di model Course
        $modules = $course->modules()->paginate(10); 
        return view('admin.modules.index', compact('course', 'modules'));
    }

    /**
     * Show the form for creating a new module for a specific course.
     */
    public function create(Course $course) // Terima objek $course
    {
        return view('admin.modules.create', compact('course'));
    }

    /**
     * Store a newly created module in storage for a specific course.
     */
    public function store(Request $request, Course $course) // Terima objek $course
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $module = new Module();
        $module->course_id = $course->id;
        $module->title = $request->title;
        $module->description = $request->description;
        // Atur order, misalnya ambil order terakhir + 1 atau default 0 jika belum ada
        $module->order = $request->filled('order') ? $request->order : ($course->modules()->max('order') + 1);
        $module->save();

        return redirect()->route('admin.courses.modules.index', $course->id)
                         ->with('success', 'Modul berhasil ditambahkan ke kursus: ' . $course->title);
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit(Course $course, Module $module) // Terima $course dan $module
    {
        // Pastikan modul yang diedit benar-benar milik kursus yang diberikan (opsional, tapi baik untuk keamanan)
        // if ($module->course_id !== $course->id) {
        //     abort(404); 
        // }
        return view('admin.modules.edit', compact('course', 'module'));
    }

    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, Course $course, Module $module) // Terima $course dan $module
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        // if ($module->course_id !== $course->id) {
        //     abort(404);
        // }

        $module->title = $request->title;
        $module->description = $request->description;
        if ($request->filled('order')) {
            $module->order = $request->order;
        }
        $module->save();

        return redirect()->route('admin.courses.modules.index', $course->id)
                         ->with('success', 'Modul berhasil diperbarui.');
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy(Course $course, Module $module) // Terima $course dan $module
    {
        // if ($module->course_id !== $course->id) {
        //     abort(404);
        // }

        // Tambahkan logika jika ada pelajaran di dalam modul yang perlu penanganan khusus
        // if ($module->lessons()->count() > 0) {
        //    return redirect()->route('admin.courses.modules.index', $course->id)
        //                     ->with('error', 'Modul tidak dapat dihapus karena masih memiliki pelajaran.');
        // }

        $module->delete();

        return redirect()->route('admin.courses.modules.index', $course->id)
                         ->with('success', 'Modul berhasil dihapus.');
    }
}
