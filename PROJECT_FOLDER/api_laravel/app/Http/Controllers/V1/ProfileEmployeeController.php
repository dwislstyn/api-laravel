<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\ParameterException;
use App\Exceptions\InvalidRuleException;
use App\Http\Controllers\Controller;
use App\Repositories\ProfileEmployeeRepository;
use App\Repositories\EmployeeRepository;
use App\Models\ProfileEmployee;
use Illuminate\Http\Request;
use stdClass;

class ProfileEmployeeController extends Controller
{
    private $employeeRepo;
    private $profileRepo;
    private $output;

    public function __construct()
    {
        $this->employeeRepo     = new EmployeeRepository();
        $this->profileRepo    = new ProfileEmployeeRepository();

        $this->output = new stdClass;
        $this->output->responseCode = '';
        $this->output->responseDesc = '';
    }

    public function insertDataProfileEmployee(Request $request)
    {
        if(empty($request->employee_id)){
            throw new ParameterException("Parameter ID pekerja tidak valid.");
        }
        
        if(!empty($request->date_of_birth) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->date_of_birth)){
            throw new ParameterException("Parameter tanggal lahir tidak valid.");
        }
        
        if(!empty($request->is_married) && !is_bool($request->is_married)){
            throw new ParameterException("Parameter status pernikahan tidak valid.");
        }

        if(!empty($request->gender) && !in_array(strtolower($request->gender), ['laki-laki','perempuan'])){
            throw new ParameterException("Parameter jenis kelamin tidak valid.");
        }

        $getProfile = $this->profileRepo->getProfileEmployee(['employee_id' => $request->employee_id]);
        $getEmployee = $this->employeeRepo->getEmployee(['id'=>$request->employee_id]);


        if(!empty($getProfile->toArray())){
            throw new InvalidRuleException("Data profile sudah ada.");
        }
        
        if(empty($getEmployee->toArray())){
            throw new DataNotFoundException("ID pekerja yang anda masukan tidak ditemukan.");
        }

        $dataProfile = new ProfileEmployee();
        $dataProfile->employee_id =$request->employee_id;
        $dataProfile->place_of_birth =empty($request->place_of_birth) ? null : $request->place_of_birth;
        $dataProfile->date_of_birth =empty($request->date_of_birth) ? null : $request->date_of_birth;
        $dataProfile->gender =empty($request->gender) ? null : $request->gender;
        $dataProfile->is_married =empty($request->is_married) ? null : $request->is_married;
        $dataProfile->created_by =empty($request->created_by) ? null : $request->created_by;
        $dataProfile->updated_by =empty($request->updated_by) ? null : $request->updated_by;

        $inserProfile = $this->profileRepo->insertDataProfile($dataProfile);
        if($inserProfile == 0){
            throw new InvalidRuleException("Insert data gagal.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Insert data berhasil';

        return response()->json($this->output);
    }
    
    public function updateDataProfileEmployee(Request $request)
    {
        if(empty($request->employee_id)){
            throw new ParameterException("Parameter ID pekerja tidak valid.");
        }

        if(!empty($request->date_of_birth) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->date_of_birth)){
            throw new ParameterException("Parameter tanggal lahir tidak valid.");
        }

        if(!empty($request->is_married) && !is_bool($request->is_married)){
            throw new ParameterException("Parameter status pernikahan tidak valid.");
        }
        
        if(!empty($request->gender) && !in_array(strtolower($request->gender), ['laki-laki','perempuan'])){
            throw new ParameterException("Parameter jenis kelamin tidak valid.");
        }

        $dataProfile = $this->profileRepo->getProfileEmployee(['employee_id' => $request->employee_id]);
        $getEmployee = $this->employeeRepo->getEmployee(['id'=>$request->employee_id]);


        if(empty($dataProfile->toArray())){
            throw new InvalidRuleException("Data profile tidak ditemukan.");
        }
        
        if(empty($getEmployee->toArray())){
            throw new DataNotFoundException("ID pekerja yang anda masukan tidak ditemukan.");
        }

        $dataProfile->employee_id = empty($request->employee_id) ? $dataProfile->employee_id : $request->employee_id;
        $dataProfile->place_of_birth = empty($request->place_of_birth) ? $dataProfile->place_of_birth : $request->place_of_birth;
        $dataProfile->date_of_birth = empty($request->date_of_birth) ? $dataProfile->date_of_birth : $request->date_of_birth;
        $dataProfile->gender = empty($request->gender) ? $dataProfile->gender : $request->gender;
        $dataProfile->is_married = empty($request->is_married) ? $dataProfile->is_married : $request->is_married;
        $dataProfile->created_by = empty($request->created_by) ? $dataProfile->created_by : $request->created_by;
        $dataProfile->updated_by = empty($request->updated_by) ? $dataProfile->updated_by : $request->updated_by;

        $inserProfile = $this->profileRepo->updateDataProfile($dataProfile);
        if($inserProfile === FALSE){
            throw new InvalidRuleException("Insert data gagal.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Update data berhasil';

        return response()->json($this->output);
    }

    public function deleteDataProfileEmployee(Request $request)
    {
        if(empty($request->employee_id)){
            throw new ParameterException("Parameter ID pekerja tidak valid.");
        }

        $deleteProfile = $this->profileRepo->deleteProfileEmployee(strval($request->employee_id));
        if($deleteProfile == 0){
            throw new InvalidRuleException("Delete data gagal.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Delete data berhasil';

        return response()->json($this->output);
    }
}
