<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\Employee;
use DB;

class EmployeeRepository{

    function getEmployee(array $data)
    {
        $query = DB::table('employee');

        if(!empty($data['id'])){
            $query->where('id', $data['id']);
        }
        
        if(!empty($data['nik'])){
            $query->where('nik', $data['nik']);
        }
        
        if(!empty($data['is_active'])){
            $query->where('is_active', $data['is_active']);
        }
        
        if(!empty($data['created_by'])){
            $query->where('created_by', $data['created_by']);
        }
        
        if(!empty($data['updated_by'])){
            $query->where('updated_by', $data['updated_by']);
        }

        $result = new Employee((array) $query->first());
        return $result;
    }
    
    function getListEmployee(array $data)
    {
        $query = DB::table('employee');

        if(!empty($data['id'])){
            $query->where('id', $data['id']);
        }
        
        if(!empty($data['nik'])){
            $query->where('nik', $data['nik']);
        }
        
        if(!empty($data['is_active'])){
            $query->where('is_active', $data['is_active']);
        }
        
        if(!empty($data['created_by'])){
            $query->where('created_by', $data['created_by']);
        }
        
        if(!empty($data['updated_by'])){
            $query->where('updated_by', $data['updated_by']);
        }

        $result = $query->get();
        return $result;
    }

    function insertEmployee(Employee $data)
    {
        $dataEmployee = $data->toArray();
        $dataEmployee['created_at'] = date('Y-m-d');
        $dataEmployee['updated_at'] = date('Y-m-d');

        $result = DB::table('employee')->insertGetId($dataEmployee);
        return $result;
    }
    
    function updateEmployee(Employee $data): bool
    {
        $dataEmployee = $data->toArray();
        $dataEmployee['updated_at'] = date('Y-m-d');

        $result = DB::table('employee')->where('id', $dataEmployee['id'])->update($dataEmployee);
        return boolval($result);
    }

    function deleteEmployee(string $id): int
    {
        $query = DB::table('employee')->where('id', $id)->delete();
        return intval($query);
    }

    function getEmployeeJoin(array $data)
    {
        $query = DB::table('employee AS e')
                    ->select(
                        'e.id AS employee_id',
                        'e.nik',
                        'e.name',
                        DB::raw("CASE WHEN e.is_active = TRUE THEN 'TRUE' ELSE 'FALSE' END AS is_active"),
                        DB::raw("DATE_PART('YEAR', AGE(CURRENT_DATE, ep.date_of_birth)) || ' Years old' AS age"),
                        'ed.name AS school_name',
                        'ed.level',
                        DB::raw("CASE 
                            WHEN total_suami > 0 OR total_anak_sambung > 0 OR total_istri > 0 OR total_anak > 0 THEN
                                CONCAT(
                                    CASE WHEN total_suami > 0 THEN CONCAT(total_suami, ' suami') ELSE '' END,
                                    CASE WHEN total_istri > 0 THEN 
                                        CASE WHEN total_suami > 0 THEN ' & ' ELSE '' END || CONCAT(total_istri, ' istri') 
                                    ELSE '' END,
                                    CASE WHEN total_anak > 0 THEN 
                                        CASE WHEN total_istri > 0 THEN ' & ' ELSE '' END || CONCAT(total_anak, ' anak') 
                                    ELSE '' END,
                                    CASE WHEN total_anak_sambung > 0 THEN 
                                        CASE WHEN total_anak > 0 THEN ' & ' ELSE '' END || CONCAT(total_anak, ' anak sambung') 
                                    ELSE '' END
                                )
                            ELSE '-' 
                        END AS family_data"))
                    ->join('employee_profile AS ep', 'e.id', '=', 'ep.employee_id')
                    ->join('education AS ed', 'e.id', '=', 'ed.employee_id')
                    ->leftJoin(DB::raw("(
                        SELECT
                            COALESCE(COUNT(CASE WHEN relation_status = 'Istri' THEN 1 END), 0) AS total_istri,
                            COALESCE(COUNT(CASE WHEN relation_status = 'Anak' THEN 1 END), 0) AS total_anak,
                            COALESCE(COUNT(CASE WHEN relation_status = 'Suami' THEN 1 END), 0) AS total_suami,
                            COALESCE(COUNT(CASE WHEN relation_status = 'Anak Sambung' THEN 1 END), 0) AS total_anak_sambung,
                            employee_id
                        FROM 
                            employee_family
                        GROUP BY
                            employee_id
                    ) AS counts"), 'e.id', '=', 'counts.employee_id');
        
        if(!empty($data['id'])){
            $query->where('e.id', $data['id']);
        }
        
        if(!empty($data['nik'])){
            $query->where('e.nik', $data['nik']);
        }
        
        if(isset($data['is_active']) && is_bool($data['is_active'])){
            $query->where('e.is_active', $data['is_active']);
        }

        if(!empty($data['created_by'])){
            $query->where('e.created_by', $data['created_by']);
        }

        if(!empty($data['updated_by'])){
            $query->where('e.updated_by', $data['updated_by']);
        }

        if(isset($data['flag_list']) && $data['flag_list'] !== FALSE){
            $result = $query->get();
        }else{
            $result = $query->first();
        }
        return $result;
    }

}