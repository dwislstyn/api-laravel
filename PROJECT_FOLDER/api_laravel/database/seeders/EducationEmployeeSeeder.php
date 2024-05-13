<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationEmployee;

class EducationEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EducationEmployee::insert([
            [
                'employee_id' => 1,
                'name' => 'SMKN 7 Jakarta',
                'level' => 'SMA',
                'description' => 'Sekolah menengah atas',
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
            [
                'employee_id' => 2,
                'name' => 'Universitas Negeri Jakarta',
                'level' => 'Strata 1',
                'description' => 'Sarjana',
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
        ]);
    }
}
