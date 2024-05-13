<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FamilyEmployee;

class FamilyEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FamilyEmployee::insert([
            [
                'employee_id' => 1,
                'name' => 'Marni',
                'indentifier' => '32100594109960002',
                'job' => 'Ibu rumah tangga',
                'place_of_birth' => 'Denpasar',
                'date_of_birth' => '1995-10-17',
                'religion' => 'Islam',
                'is_life' => true,
                'is_divorced' => false,
                'relation_status' => 'Istri',
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
            [
                'employee_id' => 1,
                'name' => 'Clara',
                'indentifier' => '32100594109960002',
                'job' => 'Pelajar',
                'place_of_birth' => 'Bangkalan',
                'date_of_birth' => '2008-10-17',
                'religion' => 'Islam',
                'is_life' => true,
                'is_divorced' => false,
                'relation_status' => 'Anak',
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
            [
                'employee_id' => 1,
                'name' => 'Stephanie',
                'indentifier' => '32100594109960002',
                'job' => 'Pelajar',
                'place_of_birth' => 'Bangkalan',
                'date_of_birth' => '2008-10-17',
                'religion' => 'Islam',
                'is_life' => true,
                'is_divorced' => false,
                'relation_status' => 'Anak',
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
        ]);
    }
}
