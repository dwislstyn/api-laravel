<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProfileEmployee;

class ProfileEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProfileEmployee::insert([
            [
                "employee_id" => 1,
                "place_of_birth" => 'Jakarta',
                "date_of_birth" => '1997-05-02',
                "gender" => 'Laki-laki',
                "is_married" => true,
                "prof_pict" => '',
                "created_by" => 'admin',
                "updated_by" => 'admin',
                "created_at" => date('Y-m-d'),
                "updated_at" => date('Y-m-d'),
            ],
            [
                "employee_id" => 2,
                "place_of_birth" => 'Sukabumi',
                "date_of_birth" => '1996-05-02',
                "gender" => 'Laki-laki',
                "is_married" => false,
                "prof_pict" => '',
                "created_by" => 'admin',
                "updated_by" => 'admin',
                "created_at" => date('Y-m-d'),
                "updated_at" => date('Y-m-d'),
            ],
        ]);
    }
}
