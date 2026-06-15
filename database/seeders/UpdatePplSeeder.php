<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pcl;
use Illuminate\Support\Facades\Hash;

class UpdatePplSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed/Update Admin Accounts
        $admins = [
            [
                'name' => 'Donny',
                'email' => 'donny@bps.go.id',
                'username' => 'donny',
            ],
            [
                'name' => 'Nur Ikhlas',
                'email' => 'nur.ikhlas@bps.go.id',
                'username' => 'nurikhlas',
            ],
        ];

        foreach ($admins as $adminData) {
            $user = User::where('email', $adminData['email'])->first();
            $password = Hash::make($adminData['username'] . '123');

            if ($user) {
                $user->update([
                    'name' => $adminData['name'],
                    'password' => $password,
                    'role' => 'admin',
                ]);
                $this->command->info("Updated admin account: {$adminData['email']}");
            } else {
                User::create([
                    'name' => $adminData['name'],
                    'email' => $adminData['email'],
                    'password' => $password,
                    'role' => 'admin',
                ]);
                $this->command->info("Created admin account: {$adminData['email']}");
            }
        }

        // 2. Seed/Update PPL (PCL) Accounts
        // Mapping of PCL changes:
        // ID 86: Andri -> Suhendri
        // ID 32: Kamaruzzaman -> Rendi Efandi
        // ID 60: Rahman Khalik -> Farid Al Farisi
        $pplChanges = [
            [
                'id' => 86,
                'old_names' => ['Andri', 'suhendri', 'Suhendri'],
                'new_name' => 'Suhendri',
                'username' => 'suhendri',
            ],
            [
                'id' => 32,
                'old_names' => ['Kamaruzaman', 'Kamaruzzaman', 'kamaruzaman', 'kamaruzzaman'],
                'new_name' => 'Rendi Efandi',
                'username' => 'rendiefandi',
            ],
            [
                'id' => 60,
                'old_names' => ['Rahman khalik', 'Rahman Khalik', 'rahman khalik'],
                'new_name' => 'Farid Al Farisi',
                'username' => 'faridalfarisi',
            ],
        ];

        foreach ($pplChanges as $change) {
            // Try to find the PCL by ID first
            $pcl = Pcl::find($change['id']);
            
            // If not found by ID, try to find by one of the names
            if (!$pcl) {
                $pcl = Pcl::whereIn('nama', $change['old_names'])->first();
            }

            $password = Hash::make($change['username'] . '123');
            $email = $change['username'] . '@semon.id';

            if ($pcl) {
                // Update Pcl
                $pcl->update([
                    'nama' => $change['new_name']
                ]);

                // Update associated User
                $user = $pcl->user;
                if ($user) {
                    $user->update([
                        'name' => $change['new_name'],
                        'email' => $email,
                        'password' => $password,
                        'role' => 'pcl',
                    ]);
                } else {
                    // Create user if somehow missing
                    $user = User::create([
                        'name' => $change['new_name'],
                        'email' => $email,
                        'password' => $password,
                        'role' => 'pcl',
                    ]);
                    $pcl->update(['user_id' => $user->id]);
                }
                $this->command->info("Updated PPL (PCL) ID {$change['id']}: {$change['new_name']}");
            } else {
                // If neither exists, create a new User & Pcl
                $user = User::create([
                    'name' => $change['new_name'],
                    'email' => $email,
                    'password' => $password,
                    'role' => 'pcl',
                ]);

                Pcl::create([
                    'id' => $change['id'],
                    'nama' => $change['new_name'],
                    'user_id' => $user->id,
                ]);
                $this->command->info("Created new PPL (PCL) ID {$change['id']}: {$change['new_name']}");
            }
        }
    }
}
