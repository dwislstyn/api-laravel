<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\InvalidRuleException;
use App\Exceptions\ParameterException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\EmployeeController;
use App\Repositories\EmployeeRepository;
use App\Repositories\EducationEmployeeRepository;
use App\Models\EducationEmployee;
use Illuminate\Http\Request;
use stdClass;

class EducationEmployeeController extends Controller
{
    private $employeeRepo;
    private $educationRepo;
    private $output;

    public function __construct()
    {
        $this->employeeRepo     = new EmployeeRepository();
        $this->educationRepo    = new EducationEmployeeRepository();

        $this->output = new stdClass;
        $this->output->responseCode = '';
        $this->output->responseDesc = '';
    }

    public function insertDataEducationEmployee(Request $request)
    {
        if(empty($request->employee_id)){
            throw new ParameterException("Parameter ID pekerja tidak valid.");
        }
        
        if(!empty($request->level) && !in_array(strtoupper($request->level), ['TK', 'SD', 'SMP', 'SMA', 'STRATA 1', 'STRATA 2', 'DOKTOR', 'PROFESSOR'])){
            throw new ParameterException("Parameter level tidak valid.");
        }
        
        if(empty($request->description)){
            throw new ParameterException("Parameter description tidak valid.");
        }
        
        if(empty($request->created_by)){
            throw new ParameterException("Parameter created_by tidak valid.");
        }
        
        if(empty($request->updated_by)){
            throw new ParameterException("Parameter updated_by tidak valid.");
        }
        
        $getEmployee = $this->employeeRepo->getEmployee(['id'=>$request->employee_id]);

        if(empty($getEmployee->toArray())){
            throw new DataNotFoundException("ID pekerja yang anda masukan tidak ditemukan.");
        }

        $education = new EducationEmployee();
        $education->employee_id = $request->employee_id;
        $education->name = !empty($request->name) ? $request->name : null;
        $education->level = !empty($request->level) ? ucwords(strtolower($request->level)) : null;
        $education->description = $request->description;
        $education->created_by = $request->created_by;
        $education->updated_by = $request->updated_by;

        $insertEducation = $this->educationRepo->insertDataEducation($education);
        if($insertEducation == 0){
            throw new InvalidRuleException("Insert data gagal.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Insert data berhasil';

        return response()->json($this->output);
    }
    
    public function updateDataEducationEmployee(Request $request)
    {
        if(empty($request->id_education)){
            throw new ParameterException("Parameter ID education tidak valid.");
        }
        
        if(!empty($request->level) && !in_array(strtoupper($request->level), ['TK', 'SD', 'SMP', 'SMA', 'STRATA 1', 'STRATA 2', 'DOKTOR', 'PROFESSOR'])){
            throw new ParameterException("Parameter level tidak valid.");
        }
        
        $dataEducation = $this->educationRepo->getEducationEmployee(['id'=>$request->id_education]);

        if(!empty($request->employee_id)){
            $getEmployee = $this->employeeRepo->getEmployee(['id'=>$request->employee_id]);
            if(empty($getEmployee->toArray())){
                throw new DataNotFoundException("ID pekerja yang anda masukan tidak ditemukan.");
            }
        }
        
        if(empty($dataEducation->toArray())){
            throw new InvalidRuleException('Data yang anda cari tidak ditemukan.');
        }

        $dataEducation->employee_id = empty($request->employee_id) ? $dataEducation->employee_id: $request->employee_id;
        $dataEducation->name = empty($request->name) ? $dataEducation->name: $request->name;
        $dataEducation->level = empty($request->level) ? $dataEducation->level:  ucwords(strtolower($request->level));
        $dataEducation->description = empty($request->description) ? $dataEducation->description: $request->description;
        $dataEducation->created_by = empty($request->created_by) ? $dataEducation->created_by: $request->created_by;
        $dataEducation->updated_by = empty($request->updated_by) ? $dataEducation->updated_by: $request->updated_by;

        $updateEducation = $this->educationRepo->updateDataEducation($dataEducation);
        if($updateEducation === FALSE){
            throw new InvalidRuleException("Insert data gagal.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Update data berhasil';

        return response()->json($this->output);
    }

    public function deleteDataEducationEmployee(Request $request)
    {
        if(empty($request->id_education)){
            throw new ParameterException("Parameter ID education tidak valid.");
        }

        $dataEducation = $this->educationRepo->getEducationEmployee(['id'=>$request->id_education]);
        if(empty($dataEducation->toArray())){
            throw new InvalidRuleException('Data yang anda cari tidak ditemukan.');
        }

        $deleteEducation = $this->educationRepo->deleteEducationEmployee(['id'=>$request->id_education]);
        if($deleteEducation == 0){
            throw new InvalidRuleException("Delete data gagal.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Delete data berhasil';

        return response()->json($this->output);
    }
}
