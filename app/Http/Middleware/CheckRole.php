<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role // Menerima parameter role dari route
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) { // Pastikan user sudah login
            return redirect('login');
        }

        /** @var \App\Models\User|null $user */ // Tambahkan PHPDoc ini
        $user = Auth::user();

        // Auth::user()->hasRole($role) adalah method yang kita buat di model User
        if (!Auth::user()->hasRole($role)) { // Gunakan parameter $role yang diterima
            // Jika tidak punya akses, bisa diarahkan ke halaman lain atau tampilkan error
            // abort(403, 'UNAUTHORIZED ACTION.');
            return redirect(route('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }
        
        return $next($request);
    }
}