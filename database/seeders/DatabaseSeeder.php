<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class, // Pastikan RoleSeeder dipanggil lebih dulu
            // Panggil seeder lain jika ada (misal CategorySeeder, dll.)
        ]);

        // 1. Dapatkan objek peran 'administrator'
        $adminRole = Role::where('name', 'administrator')->first();

        // 2. Buat atau temukan pengguna admin
        // Gunakan firstOrCreate agar tidak duplikat jika seeder dijalankan berkali-kali
        if ($adminRole) {
            $adminUser = User::firstOrCreate(
                ['email' => 'admin@basstrainingacademy.com'], // Kriteria untuk mencari/membuat
                [ // Data yang akan diisi jika user baru dibuat
                    'name' => 'Admin LMS',
                    'password' => Hash::make('samphistik7'), // GANTI DENGAN PASSWORD YANG AMAN!
                    'email_verified_at' => now(), // Jika Anda menggunakan verifikasi email
                ]
            );
            // 3. Tugaskan peran administrator ke pengguna tersebut
            // syncWithoutDetaching agar tidak menghapus peran lain jika ada (meski untuk admin biasanya hanya satu)
            $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);
            $this->command->info('Admin user created/updated and role assigned: admin@basstrainingacademy.com');
        } else {
            $this->command->error('Administrator role not found. Admin user not seeded.');
        }

        // Opsional: Buat beberapa pengguna contoh dengan peran 'student'
        $studentRole = Role::where('name', 'student')->first(); // atau 'peserta'
        if ($studentRole) {
            User::factory()->count(5)->create()->each(function ($user) use ($studentRole) {
                $user->roles()->attach($studentRole->id);
            });
            $this->command->info('5 sample student users created.');
        }

        // Opsional: Buat beberapa pengguna contoh dengan peran 'instructor'
        $instructorRole = Role::where('name', 'instructor')->first();
        if ($instructorRole) {
            User::factory()->count(2)->create()->each(function ($user) use ($instructorRole) {
                $user->roles()->attach($instructorRole->id);
            });
            $this->command->info('2 sample instructor users created.');
        }
    }
}