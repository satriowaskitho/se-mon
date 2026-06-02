<?php

namespace App\Imports;

use App\Models\District;
use App\Models\Village;
use App\Models\Sls;
use App\Models\SubSls;
use App\Models\Pml;
use App\Models\Pcl;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SemonDataImport
{
    protected string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function import(): void
    {
        DB::transaction(function () {
            $spreadsheet = IOFactory::load($this->filePath);

            // 1. Clear existing assignments/daily_reports to prevent key duplication on re-seed
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Assignment::query()->delete();
            Pcl::query()->delete();
            Pml::query()->delete();
            SubSls::query()->delete();
            Sls::query()->delete();
            Village::query()->delete();
            District::query()->delete();
            // Delete users with role 'pcl' or 'pml'
            User::whereIn('role', ['pcl', 'pml'])->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // 2. Import Districts
            $sheet = $spreadsheet->getSheetByName('districts');
            if ($sheet) {
                $rows = $sheet->toArray();
                $header = array_shift($rows);
                // Columns: ['idkec', 'kdkab', 'nmkab', 'kdkec', 'nmkec']
                foreach ($rows as $row) {
                    if (empty($row[0])) continue;
                    District::create([
                        'idkec' => trim($row[0]),
                        'kdkab' => trim($row[1]),
                        'nmkab' => trim($row[2]),
                        'kdkec' => trim($row[3]),
                        'nmkec' => trim($row[4]),
                        'idkab' => '2102', // Derived Province 21 (Kepri) + Kab 02 (Bintan)
                    ]);
                }
            }

            // 3. Import Villages
            $sheet = $spreadsheet->getSheetByName('villages');
            if ($sheet) {
                $rows = $sheet->toArray();
                $header = array_shift($rows);
                // Columns: ['iddesa', 'idkec', 'kddesa', 'nmdesa']
                foreach ($rows as $row) {
                    if (empty($row[0])) continue;
                    Village::create([
                        'iddesa' => trim($row[0]),
                        'idkec' => trim($row[1]),
                        'kddesa' => trim($row[2]),
                        'nmdesa' => trim($row[3]),
                    ]);
                }
            }

            // 4. Import SLS
            $sheet = $spreadsheet->getSheetByName('sls');
            if ($sheet) {
                $rows = $sheet->toArray();
                $header = array_shift($rows);
                // Columns: ['idsls', 'iddesa', 'kdsls', 'nmsls']
                foreach ($rows as $row) {
                    if (empty($row[0])) continue;
                    Sls::create([
                        'idsls' => trim($row[0]),
                        'iddesa' => trim($row[1]),
                        'kdsls' => trim($row[2]),
                        'nmsls' => trim($row[3]),
                    ]);
                }
            }

            // 5. Import SubSLS
            $sheet = $spreadsheet->getSheetByName('subsls');
            if ($sheet) {
                $rows = $sheet->toArray();
                $header = array_shift($rows);
                // Columns: ['idsubsls', 'idsubsls_rebuild', 'idsls', 'kdsubsls']
                foreach ($rows as $row) {
                    if (empty($row[0])) continue;
                    SubSls::create([
                        'idsubsls' => trim($row[0]),
                        'idsls' => trim($row[2]),
                        'kdsubsls' => trim($row[3]),
                    ]);
                }
            }

            // 6. Import PMLs
            $sheet = $spreadsheet->getSheetByName('pmls');
            $pmlMapByName = [];
            if ($sheet) {
                $rows = $sheet->toArray();
                $header = array_shift($rows);
                // Columns: ['nama', 'pml_id']
                foreach ($rows as $row) {
                    if (empty($row[1])) continue;
                    $nama = trim($row[0]);
                    $pmlId = (int)$row[1];

                    // Generate account: lowercase name without spaces + 123
                    $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama));
                    $email = $username . '@semon.id';
                    $password = $username . '123';

                    $user = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role' => 'pml',
                    ]);

                    $pml = Pml::create([
                        'id' => $pmlId,
                        'nama' => $nama,
                        'user_id' => $user->id,
                    ]);

                    $pmlMapByName[$nama] = $pml->id;
                }
            }

            // 7. Import PCLs
            $sheet = $spreadsheet->getSheetByName('pcls');
            $pclMapByName = [];
            if ($sheet) {
                $rows = $sheet->toArray();
                $header = array_shift($rows);
                // Columns: ['nama', 'pcl_id']
                foreach ($rows as $row) {
                    if (empty($row[1])) continue;
                    $nama = trim($row[0]);
                    $pclId = (int)$row[1];

                    // Generate account: lowercase name without spaces + 123
                    $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama));
                    $email = $username . '@semon.id';
                    $password = $username . '123';

                    $user = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role' => 'pcl',
                    ]);

                    $pcl = Pcl::create([
                        'id' => $pclId,
                        'nama' => $nama,
                        'user_id' => $user->id,
                    ]);

                    $pclMapByName[$nama] = $pcl->id;
                }
            }

            // 8. Import Assignments
            $sheet = $spreadsheet->getSheetByName('assignments');
            if ($sheet) {
                $rows = $sheet->toArray();
                $header = array_shift($rows);
                // Columns: ['idsubsls', 'idsls', 'iddesa', 'idkec', 'Muatan Keseluruhan', 'PCL', 'PML']
                foreach ($rows as $row) {
                    if (empty($row[0])) continue;
                    $idsubsls = trim($row[0]);
                    $targetUsaha = (int)$row[4];
                    $pclName = trim($row[5]);
                    $pmlName = trim($row[6]);

                    // Resolve PCL & PML IDs by name (fallback to first record or null if not found)
                    $pclId = $pclMapByName[$pclName] ?? null;
                    if (!$pclId) {
                        // try case-insensitive search
                        foreach ($pclMapByName as $name => $id) {
                            if (strcasecmp($name, $pclName) === 0) {
                                $pclId = $id;
                                break;
                            }
                        }
                    }

                    $pmlId = $pmlMapByName[$pmlName] ?? null;
                    if (!$pmlId) {
                        foreach ($pmlMapByName as $name => $id) {
                            if (strcasecmp($name, $pmlName) === 0) {
                                $pmlId = $id;
                                break;
                            }
                        }
                    }

                    if ($pclId && $pmlId) {
                        Assignment::create([
                            'idsubsls' => $idsubsls,
                            'pcl_id' => $pclId,
                            'pml_id' => $pmlId,
                            'target_usaha' => $targetUsaha,
                        ]);
                    }
                }
            }
        });
    }
}
