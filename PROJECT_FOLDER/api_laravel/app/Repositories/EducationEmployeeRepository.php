<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\EducationEmployee;
use DB;

class EducationEmployeeRepository{

    function getEducationEmployee(array $data)
    {
        $query = DB::table('education');

        if(!empty($data['id'])){
            $query->where('id', $data['id']);
        }
        
        if(!empty($data['employee_id'])){
            $query->where('employee_id', $data['employee_id']);
        }

        $result = new EducationEmployee((array) $query->first());
        return $result;
    }

    function insertDataEducation(EducationEmployee $data): int
    {
        $dataEducation = $data->toArray();
        $dataEducation['created_at'] = date('Y-m-d');
        $dataEducation['updated_at'] = date('Y-m-d');

        $result = DB::table('education')->insert($dataEducation);
        return intval($result);
    }
    
    function updateDataEducation(EducationEmployee $data): bool
    {
        $dataEducation = $data->toArray();
        $dataEducation['updated_at'] = date('Y-m-d');

        $query = DB::table('education');
        
        if(!empty($data['id'])){
            $query->where('id', $dataEducation['id']);
        }
        
        if(!empty($data['employee_id'])){
            $query->where('employee_id', $dataEducation['employee_id']);
        }
        
        $result = $query->update($dataEducation);
        return boolval($result);
    }

    function deleteEducationEmployee(array $data): int
    {
        $query = DB::table('education');

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