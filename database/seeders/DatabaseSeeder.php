<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pml;
use App\Models\Pcl;
use App\Models\Assignment;
use App\Models\DailyReport;
use App\Models\SubSls;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Imports\SemonDataImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Initial Excel Geographical & Operational Import
        $excelPath = base_path('SE2026_Normalized_Seeder_StringFixed.xlsx');
        if (file_exists($excelPath)) {
            $this->command->info("=== MEMULAI IMPORT GEOGRAFIS & OPERASIONAL DARI EXCEL ===");
            $import = new SemonDataImport($excelPath);
            $import->import();
            $this->command->info("Excel Import completed successfully!");
        } else {
            $this->command->error("Excel seeder file not found at: {$excelPath}");
            return;
        }

        // 2. Clear Daily Reports & recreate Admin account
        $this->command->info("\n=== MEMBERSIHKAN DATA HARIAN & MEMBUAT AKUN ADMIN ===");
        Schema::withoutForeignKeyConstraints(function () {
            if (DB::getDriverName() === 'pgsql') {
                DB::statement('TRUNCATE TABLE daily_reports RESTART IDENTITY CASCADE');
            } else {
                DailyReport::truncate();
            }
        
            User::where('email', 'admin@semon.id')->delete();
        });

        // 3. Create Admin User
        User::create([
            'name' => 'Admin SEMON',
            'email' => 'admin@semon.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        $this->command->info("Admin User created successfully: admin@semon.id / admin123");

        // Clean cache after seeding
        \Illuminate\Support\Facades\Cache::forget('kabupaten_stats');
        \Illuminate\Support\Facades\Cache::forget('landing_stats');
        \Illuminate\Support\Facades\Cache::forget('map_progress');

        $this->command->info("\n=== SEEDER SELESAI DIJALANKAN DENGAN SUKSES ===");
    }
}
