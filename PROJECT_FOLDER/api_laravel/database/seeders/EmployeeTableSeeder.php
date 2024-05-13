<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::insert([
            [
                'nik' => '11012',
                'name' => 'Budi',
                'is_active' => true,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d'),
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
            [
                'nik' => '11013',
                'name' => 'Jarot',
                'is_active' => true,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d'),
                'created_by' => 'Dwi Sulistyo Nugroho',
                'updated_by' => 'Dwi Sulistyo Nugroho',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
        ]);
    }
}
