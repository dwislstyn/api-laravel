<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\ProfileEmployee;
use DB;

class ProfileEmployeeRepository{

    function getProfileEmployee(array $data)
    {
        $query = DB::table('employee_profile');

        if(!empty($data['id'])){
            $query->where('id', $data['id']);
        }
        
        if(!empty($data['employee_id'])){
            $query->where('employee_id', $data['employee_id']);
        }

        $result = new ProfileEmployee((array) $query->first());
        return $result;
    }

    function insertDataProfile(ProfileEmployee $data): int
    {
        $dataProfile = $data->toArray();
        $dataProfile['created_at'] = date('Y-m-d');
        $dataProfile['updated_at'] = date('Y-m-d');

        $result = DB::table('employee_profile')->insert($dataProfile);
        return intval($result);
    }
    
    function updateDataProfile(ProfileEmployee $data): bool
    {
        $dataProfile = $data->toArray();
        $dataProfile['updated_at'] = date('Y-m-d');

        $query = DB::table('employee_profile');
        
        if(!empty($data['id'])){
            $query->where('id', $dataProfile['id']);
        }
        
        if(!empty($data['employee_id'])){
            $query->where('employee_id', $dataProfile['employee_id']);
        }

        $result = $query->update($dataProfile);
        return boolval($result);
    }

    function deleteProfileEmployee(string $id): int
    {
        $query = DB::table('employee_profile')->where('employee_id', $id)->delete();
        return intval($query);
    }

}