<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\FamilyEmployee;
use DB;

class FamilyEmployeeRepository{

    function getFamilytEmployee(array $data)
    {
        $query = DB::table('employee_family');

        if(!empty($data['id'])){
            $query->where('id', $data['id']);
        }
        
        if(!empty($data['employee_id'])){
            $query->where('employee_id', $data['employee_id']);
        }

        $result = new FamilyEmployee((array) $query->first());
        return $result;
    }

    function insertDataFamily(FamilyEmployee $data): int
    {
        $dataFamily = $data->toArray();
        $dataFamily['created_at'] = date('Y-m-d');
        $dataFamily['updated_at'] = date('Y-m-d');

        $result = DB::table('employee_family')->insert($dataFamily);
        return intval($result);
    }
    
    function updateDataFamily(FamilyEmployee $data): bool
    {
        $dataFamily = $data->toArray();
        $dataFamily['updated_at'] = date('Y-m-d');

        $query = DB::table('employee_family');

        if(!empty($data['id'])){
            $query->where('id', $dataFamily['id']);
        }
        
        if(!empty($data['employee_id'])){
            $query->where('employee_id', $dataFamily['employee_id']);
        }

        $result = $query->update($dataFamily);
        return boolval($result);
    }

    function deleteFamilyEmployee(array $data): int
    {
        $query = DB::table('employee_family');
        
        if(!empty($data['id'])){
            $query->where('id', $data['id']);
        }
        
        if(!empty($data['employee_id'])){
            $query->where('employee_id', $data['employee_id']);
        }

        $result = $query->delete();
        return intval($result);
    }

}