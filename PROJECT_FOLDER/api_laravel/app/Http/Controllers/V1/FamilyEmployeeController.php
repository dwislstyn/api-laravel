<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ParameterException;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\InvalidRuleException;
use App\Http\Controllers\Controller;
use App\Repositories\EmployeeRepository;
use App\Repositories\FamilyEmployeeRepository;
use App\Models\FamilyEmployee;
use Illuminate\Http\Request;
use stdClass;

class FamilyEmployeeController extends Controller
{
    private $employeeRepo;
    private $familyRepo;
    private $output;

    public function __construct()
    {
        $this->employeeRepo = new EmployeeRepository();
        $this->familyRepo = new FamilyEmployeeRepository();

        $this->output = new stdClass;
        $this->output->responseCode = '';
        $this->output->responseDesc = '';
    }
    
    public function insertDataFamilyEmployee(Request $request)
    {
        if(empty($request->employee_id)){
            throw new ParameterException("Parameter ID pekerja tidak valid.");
        }
        
        if(empty($request->name)){
            throw new ParameterException("Parameter name tidak valid.");
        }
        
        if(empty($request->indentifier)){
            throw new ParameterException("Parameter indentifier tidak valid.");
        }
        
        if(empty($request->job)){
            throw new ParameterException("Parameter job tidak valid.");
        }
        
        if(!isset($request->is_life) || !is_bool($request->is_life)){
            throw new ParameterException("Parameter is_life tidak valid.");
        }
        
        if(!isset($request->is_divorced) || !is_bool($request->is_divorced)){
            throw new ParameterException("Parameter is_divorced tidak valid.");
        }

        if(empty($request->religion) && !in_array(strtolower($request->religion), ['islam', 'katolik', 'budha', 'protestan', 'khonghucu'])){
            throw new ParameterException("Parameter religion tidak valid.");
        }
        
        if(empty($request->relation_status) || !in_array(strtolower($request->relation_status), ['suami', 'istri', 'anak', 'anak sambung'])){
            throw new ParameterException("Parameter hubungan tidak valid.");
        }

        $getEmployee = $this->employeeRepo->getEmployee(['id'=>$request->employee_id]);
        
        if(empty($getEmployee->toArray())){
            throw new DataNotFoundException("ID pekerja yang anda masukan tidak ditemukan.");
        }

        $dataFamily = new FamilyEmployee();
        $dataFamily->employee_id = $request->employee_id;
        $dataFamily->name = empty($request->name) ? null : $request->name;
        $dataFamily->indentifier = empty($request->indentifier) ? null : $request->indentifier;
        $dataFamily->job = empty($request->job) ? null : $request->job;
        $dataFamily->place_of_birth = empty($request->place_of_birth) ? null : $request->place_of_birth;
        $dataFamily->date_of_birth = empty($request->date_of_birth) ? null : $request->date_of_birth;
        $dataFamily->religion = empty($request->religion) ? null : ucwords($request->religion);
        $dataFamily->is_life = $request->is_life == FALSE ? true : $request->is_life;
        $dataFamily->is_divorced = $request->is_divorced == FALSE ? false : $request->is_divorced;
        $dataFamily->relation_status = empty($request->relation_status) ? null : ucwords($request->relation_status);
        $dataFamily->created_by = empty($request->created_by) ? null : $request->created_by;
        $dataFamily->updated_by = empty($request->updated_by) ? null : $request->updated_by;

        $insertFamily = $this->familyRepo->insertDataFamily($dataFamily);
        if($insertFamily == 0){
            throw new InvalidRuleException("Insert data gagal.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Insert data berhasil';

        return response()->json($this->output);
    }
    
    public function updateDataFamilyEmployee(Request $request)
    {
        if(empty($request->family_id)){
            throw new ParameterException("Parameter ID anggota keluarga tidak valid.");
        }

        if(!empty($request->religion) && !in_array(strtolower($request->religion), ['islam', 'katolik', 'budha', 'protestan', 'khonghucu'])){
            throw new ParameterException("Parameter religion tidak valid.");
        }

        if(!empty($request->relation_status) && !in_array(strtolower($request->relation_status), ['suami', 'istri', 'anak', 'anak sambung'])){
            throw new ParameterException("Parameter hubungan tidak valid.");
        }

        $dataFamily = $this->familyRepo->getFamilytEmployee(['id'=>$request->family_id]);
        if(empty($dataFamily->toArray())){
            throw new InvalidRuleException('Data anggota keluarga tidak ditemukan.');
        }

        if(!empty($request->employee_id)){
            $getEmployee = $this->employeeRepo->getEmployee(['id'=>$request->employee_id]);
            if(empty($getEmployee->toArray())){
                throw new DataNotFoundException("ID pekerja yang anda masukan tidak ditemukan.");
            }
        }

        $dataFamily->employee_id = empty($request->employee_id) ? $dataFamily->employee_id : $request->employee_id;
        $dataFamily->name = empty($request->name) ? $dataFamily->name : $request->name;
        $dataFamily->indentifier = empty($request->indentifier) ? $dataFamily->indentifier : $request->indentifier;
        $dataFamily->job = empty($request->job) ? $dataFamily->job : $request->job;
        $dataFamily->place_of_birth = empty($request->place_of_birth) ? $dataFamily->place_of_birth : $request->place_of_birth;
        $dataFamily->date_of_birth = empty($request->date_of_birth) ? $dataFamily->date_of_birth : $request->date_of_birth;
        $dataFamily->religion = empty($request->religion) ? $dataFamily->religion : ucwords($request->religion);
        $dataFamily->is_life = !isset($request->is_life) ? $dataFamily->is_life : $request->is_life;
        $dataFamily->is_divorced = !isset($request->is_divorced) ? $dataFamily->is_divorced : $request->is_divorced;
        $dataFamily->relation_status = empty($request->relation_status) ? $dataFamily->relation_status : $request->relation_status;
        $dataFamily->created_by = empty($request->created_by) ? $dataFamily->created_by : $request->created_by;
        $dataFamily->updated_by = empty($request->updated_by) ? $dataFamily->updated_by : $request->updated_by;

        $updateFamily = $this->familyRepo->updateDataFamily($dataFamily);
        if($updateFamily === FALSE){
            throw new InvalidRuleException("Update data gagal.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Update data berhasil';

        return response()->json($this->output);
    }

    public function deleteFamilyEmployee(Request $request)
    {
        if(empty($request->family_id)){
            throw new ParameterException("Parameter ID keluarga tidak valid.");
        }

        $dataFamily = $this->familyRepo->getFamilytEmployee(['id'=>$request->family_id]);
        if(empty($dataFamily->toArray())){
            throw new InvalidRuleException('Data anggota keluarga tidak ditemukan.');
        }

        $deleteFamily = $this->familyRepo->deleteFamilyEmployee(['id'=>$request->family_id]);
        if($deleteFamily == 0){
            throw new InvalidRuleException("Delete data gagal.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Delete data berhasil';

        return response()->json($this->output);
    }
}
