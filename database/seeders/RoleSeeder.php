<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'administrator'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'instructor'], ['display_name' => 'Instruktur']);
        Role::firstOrCreate(['name' => 'student'], ['display_name' => 'Peserta']); // 'student' atau 'peserta'
    }
}
