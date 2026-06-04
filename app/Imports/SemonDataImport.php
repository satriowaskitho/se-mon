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

            // 1. Clear existing master/assignment data before re-seed
            if (DB::getDriverName() === 'pgsql') {
                DB::statement('TRUNCATE TABLE assignment, pcl, pml, sub_sls, sls, village, district RESTART IDENTITY CASCADE');
                User::whereIn('role', ['pcl', 'pml'])->delete();
            } else {
                DB::table('assignment')->truncate();
                DB::table('pcl')->truncate();
                DB::table('pml')->truncate();
                DB::table('sub_sls')->truncate();
                DB::table('sls')->truncate();
                DB::table('village')->truncate();
                DB::table('district')->truncate();
                User::whereIn('role', ['pcl', 'pml'])->delete();
            }

            // 2. Import Districts
            $sheet = $spreadsheet->getSheetByName('districts');
            if ($sheet) {
                $rows = $sheet->toArray();
                array_shift($rows);

                foreach ($rows as $row) {
                    if (empty($row[0])) continue;

                    District::create([
                        'idkec' => trim($row[0]),
                        'kdkab' => trim($row[1]),
                        'nmkab' => trim($row[2]),
                        'kdkec' => trim($row[3]),
                        'nmkec' => trim($row[4]),
                        'idkab' => '2102',
                    ]);
                }
            }

            // 3. Import Villages
            $sheet = $spreadsheet->getSheetByName('villages');
            if ($sheet) {
                $rows = $sheet->toArray();
                array_shift($rows);

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
                array_shift($rows);

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
                array_shift($rows);

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
                array_shift($rows);

                foreach ($rows as $row) {
                    if (empty($row[1])) continue;

                    $nama = trim($row[0]);
                    $pmlId = (int) $row[1];
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
                array_shift($rows);

                foreach ($rows as $row) {
                    if (empty($row[1])) continue;

                    $nama = trim($row[0]);
                    $pclId = (int) $row[1];
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
                array_shift($rows);

                foreach ($rows as $row) {
                    if (empty($row[0])) continue;

                    $idsubsls = trim($row[0]);
                    $targetUsaha = (int) $row[4];
                    $pclName = trim($row[5]);
                    $pmlName = trim($row[6]);

                    $pclId = $pclMapByName[$pclName] ?? null;
                    if (!$pclId) {
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
