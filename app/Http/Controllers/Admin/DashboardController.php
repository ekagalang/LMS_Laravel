<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Untuk statistik
// use App\Models\Course; // Nanti jika sudah ada model Course

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        // $totalCourses = Course::count(); // Nanti
        // Data lain yang ingin ditampilkan di dashboard

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            // 'totalCourses' => $totalCourses,
        ]);
    }
}