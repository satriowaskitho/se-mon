<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProvinsiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'bps2100@bps.go.id';
        $user = User::where('email', $email)->first();
        $password = Hash::make('Monitor2100');

        if ($user) {
            $user->update([
                'name' => 'BPS Provinsi KEPRI',
                'password' => $password,
                'role' => 'provinsi',
            ]);
            $this->command->info("Updated Provinsi user: {$email}");
        } else {
            User::create([
                'name' => 'BPS Provinsi KEPRI',
                'email' => $email,
                'password' => $password,
                'role' => 'provinsi',
            ]);
            $this->command->info("Created Provinsi user: {$email}");
        }
    }
}
