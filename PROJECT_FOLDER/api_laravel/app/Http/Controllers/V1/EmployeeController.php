<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\InvalidRuleException;
use App\Exceptions\ParameterException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\FamilyEmployeeController;
use App\Http\Controllers\V1\EducationEmployeeController;
use App\Models\EducationEmployee;
use App\Models\Employee;
use App\Models\FamilyEmployee;
use App\Models\ProfileEmployee;
use App\Repositories\EducationEmployeeRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\FamilyEmployeeRepository;
use App\Repositories\ProfileEmployeeRepository;
use Illuminate\Http\Request;
use stdClass;
use Mpdf\Mpdf;
use DB;

class EmployeeController extends Controller
{

    private $employeeRepo;
    private $profileRepo;
    private $familyRepo;
    private $educationtRepo;
    private $output;

    public function __construct()
    {
        $this->employeeRepo = new EmployeeRepository();
        $this->profileRepo = new ProfileEmployeeRepository();
        $this->familyRepo = new FamilyEmployeeRepository();
        $this->familyRepo = new FamilyEmployeeRepository();
        $this->educationtRepo = new EducationEmployeeRepository();

        $this->output = new stdClass();
        $this->output->responseCode = '';
        $this->output->responseDesc = '';
    }

    public function inquiryListEmployee(Request $request)
    {
        $filterEmployee = [
            'id'            => !empty($request->id) ? $request->id : null,
            'nik'           => !empty($request->nik) ? $request->nik : null,
            'name'          => !empty($request->name) ? $request->name : null,
            'is_active'     => !empty($request->is_active) ? $request->is_active : null,
            'created_by'    => !empty($request->created_by) ? $request->created_by : null,
            'updated_by'    => !empty($request->updated_by) ? $request->updated_by : null,
        ];

        $getListEmployee = $this->employeeRepo->getListEmployee($filterEmployee);
        if(empty($getListEmployee->toArray())){
            throw new DataNotFoundException("Data pekerja tidak ditemukan");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Inquiry list pekerja berhasil';
        $this->output->responseData = $getListEmployee;

        return response()->json($this->output);
    }

    public function inquiryEmployee(Request $request)
    {
        if(empty($request->id) && empty($request->nik)){
            throw new InvalidRuleException("Anda harus memasukan data ID/NIK pekerja");
        }

        $filterEmployee = [
            'id'            => !empty($request->id) ? $request->id : null,
            'nik'           => !empty($request->nik) ? $request->nik : null,
            'name'          => !empty($request->name) ? $request->name : null,
            'is_active'     => isset($request->is_active) ? $request->is_active : null,
            'created_by'    => !empty($request->created_by) ? $request->created_by : null,
            'updated_by'    => !empty($request->updated_by) ? $request->updated_by : null,
        ];

        $getEmployee = $this->employeeRepo->getEmployeeJoin($filterEmployee);
        if(empty($getEmployee)){
            throw new DataNotFoundException("Data pekerja tidak ditemukan.");
        }

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Inquiry data pekerja berhasil';
        $this->output->responseData = $getEmployee;

        return response()->json($this->output);
    }
    

    public function insertDataEmployee(Request $request)
    {
        if(empty($request->nik)){
            throw new ParameterException("Parameter NIK pekerja tidak valid.");
        }
        
        if(empty($request->name)){
            throw new ParameterException("Parameter nama pekerja tidak valid.");
        }
        
        if(empty($request->created_by)){
            throw new ParameterException("Parameter created_by tidak valid.");
        }

        if(empty($request->updated_by)){
            throw new ParameterException("Parameter updated_by tidak valid.");
        }
        
        if(isset($request->is_active) && !is_bool($request->is_active)){
            throw new ParameterException("Parameter is_active tidak valid.");
        }

        if(empty($request->start_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->start_date)){
            throw new ParameterException("Parameter start_date tidak valid.");
        }
        
        if(empty($request->end_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->end_date)){
            throw new ParameterException("Parameter end_date tidak valid.");
        }
        
        if(empty($request->data_keluarga) || !is_array($request->data_keluarga)){
            throw new ParameterException("Parameter data keluarga tidak valid.");
        }
        
        if(empty($request->data_education) || !is_array($request->data_education)){
            throw new ParameterException("Parameter data education pekerja tidak valid.");
        }
        
        if(empty($request->data_profile) || !is_array($request->data_profile)){
            throw new ParameterException("Parameter data profile tidak valid.");
        }

        $getEmployee = $this->employeeRepo->getEmployee(['nik'=>$request->nik]);
        if(!empty($getEmployee->toArray())){
            throw new DataNotFoundException("NIK data pekerja yang anda masukan sudah ada");
        }

        $dataPekerja = new Employee();
        $dataPekerja->nik = $request->nik;
        $dataPekerja->name = $request->name;
        $dataPekerja->start_date = $request->start_date;
        $dataPekerja->end_date = $request->end_date;
        $dataPekerja->is_active = isset($request->is_active) ? $request->is_active : null;
        $dataPekerja->created_by = !empty($request->created_by) ? $request->created_by : null;
        $dataPekerja->updated_by = !empty($request->updated_by) ? $request->updated_by : null;


        DB::transaction(function () use ($dataPekerja, $request){
            $insertPekerja = $this->employeeRepo->insertEmployee($dataPekerja);
            if($insertPekerja == 0){
                throw new InvalidRuleException("Insert data gagal.");
            }
            
            // Insert data profile
            $profileController = new ProfileEmployeeController();
            $dataProfile = $request->data_profile;
            $dataProfile['employee_id'] = $insertPekerja;
            $requestProfile = new Request($dataProfile);
            $insertProfile = $profileController->insertDataProfileEmployee($requestProfile);
            $responseProfile = json_decode($insertProfile->getContent());
            if(!isset($responseProfile->responseCode) || $responseProfile->responseCode != '00'){
                $msgProfile = empty($responseProfile->responseDesc) ? 'Gagal insert data keluarga' : $responseProfile->responseDesc;
                throw new InvalidRuleException("Error hit insertDataProfile: $msgProfile");
            }

            // Insert data family
            $familyController = new FamilyEmployeeController();
            $dataKeluarga = collect($request->data_keluarga)->transform(function($itemKeluarga) use ($insertPekerja){
                $itemKeluarga['employee_id'] = intval($insertPekerja);
                return $itemKeluarga;
            });

            foreach ($dataKeluarga as $listKeluarga) {
                $requestFamily = new Request($listKeluarga);
                $insertFamily = $familyController->insertDataFamilyEmployee($requestFamily);
                $responseFamily = json_decode($insertFamily->getContent());
                if(!isset($responseFamily->responseCode) || $responseFamily->responseCode != '00'){
                    $msgFamily = empty($responseFamily->responseDesc) ? 'Gagal insert data keluarga' : $responseFamily->responseDesc;
                    throw new InvalidRuleException("Error hit insertDataFamily: $msgFamily");
                }
            }

            // Insert data education
            $educationController = new EducationEmployeeController();
            $dataEducation = collect($request->data_education)->transform(function($itemEducation) use ($insertPekerja){
                $itemEducation['employee_id'] = intval($insertPekerja);
                return $itemEducation;
            });

            foreach ($dataEducation as $listEducation) {
                $requestEducation = new Request($listEducation);
                $insertEducation = $educationController->insertDataEducationEmployee($requestEducation);
                $responseEducation = json_decode($insertEducation->getContent());
                if(!isset($responseEducation->responseCode) || $responseEducation->responseCode != '00'){
                    $msgEducation = empty($responseEducation->responseDesc) ? 'Gagal insert data education' : $responseEducation->responseDesc;
                    throw new InvalidRuleException("Error hit insertDataEducation: $msgEducation");
                }
            }
        });

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Insert data berhasil';

        return response()->json($this->output);
    }
    
    public function updateDataEmployee(Request $request)
    {
        if(empty($request->nik)){
            throw new ParameterException("Parameter NIK pekerja tidak valid.");
        }
        
        if(empty($request->name)){
            throw new ParameterException("Parameter nama pekerja tidak valid.");
        }
        
        if(empty($request->created_by)){
            throw new ParameterException("Parameter created_by tidak valid.");
        }

        if(empty($request->updated_by)){
            throw new ParameterException("Parameter updated_by tidak valid.");
        }
        
        if(isset($request->is_active) && !is_bool($request->is_active)){
            throw new ParameterException("Parameter is_active tidak valid.");
        }

        if(empty($request->start_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->start_date)){
            throw new ParameterException("Parameter start_date tidak valid.");
        }
        
        if(empty($request->end_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->end_date)){
            throw new ParameterException("Parameter end_date tidak valid.");
        }

        $dataPekerja = $this->employeeRepo->getEmployee(['nik'=>$request->nik]);
        if(empty($dataPekerja->toArray())){
            throw new DataNotFoundException("NIK anda cari tidak ada.");
        }

        $dataPekerja->nik = empty($request->nik) ? $dataPekerja->nik : $request->nik;
        $dataPekerja->name = empty($request->name) ? $dataPekerja->name : $request->name;
        $dataPekerja->start_date = empty($request->start_date) ? $dataPekerja->start_date : $request->start_date;
        $dataPekerja->end_date = empty($request->end_date) ? $dataPekerja->end_date : $request->end_date;
        $dataPekerja->is_active = !isset($request->is_active) ? $dataPekerja->is_active : $request->is_active;
        $dataPekerja->created_by = empty($request->created_by) ? $dataPekerja->created_by : $request->created_by;
        $dataPekerja->updated_by = empty($request->updated_by) ? $dataPekerja->updated_by : $request->updated_by;

        DB::transaction(function () use ($dataPekerja, $request){
            $updatePekerja = $this->employeeRepo->updateEmployee($dataPekerja);
            if($updatePekerja == FALSE){
                throw new InvalidRuleException("Insert data gagal.");
            }
            
            // Update data profile
            $dataProfile = empty($request->data_profile) && !is_array($request->data_profile)? [] : $request->data_profile;
            $dataProfile['employee_id'] = $dataPekerja->id;
            $updateProfile = $this->profileRepo->updateDataProfile(new ProfileEmployee($dataProfile));
            if($updateProfile === FALSE){
                throw new InvalidRuleException("Update data profile gagal.");
            }

            // Update data family
            $dataKeluarga = empty($request->data_keluarga) && !is_array($request->data_keluarga)? collect() : collect($request->data_keluarga);
            $dataKeluarga->transform(function($itemKeluarga) use ($dataPekerja){
                $itemKeluarga['employee_id'] = intval($dataPekerja->id);
                return $itemKeluarga;
            });

            foreach ($dataKeluarga as $listKeluarga) {
                if(empty($listKeluarga['id'])){
                    throw new ParameterException("Terdapat parameter ID keluarga yang tidak valid.");
                }

                $updateFamily = $this->familyRepo->updateDataFamily(new FamilyEmployee($listKeluarga));
                if($updateFamily === FALSE){
                    throw new InvalidRuleException("Update data family gagal.");
                }
            }

            // Update data education
            $dataEducation = empty($request->data_education) && !is_array($request->data_education)? collect() : collect($request->data_education);
            $dataEducation->transform(function($itemEducation) use ($dataPekerja){
                $itemEducation['employee_id'] = intval($dataPekerja->id);
                return $itemEducation;
            });

            foreach ($dataEducation as $listEducation) {
                if(empty($listEducation['id'])){
                    throw new ParameterException("Terdapat parameter ID education yang tidak valid.");
                }

                $updateEducation = $this->educationtRepo->updateDataEducation(new EducationEmployee($listEducation));
                if($updateEducation === FALSE){
                    throw new InvalidRuleException("Update data education gagal.");
                }
            }
        });

        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Update data berhasil';

        return response()->json($this->output);
    }

    public function deleteDataEmployee(Request $request)
    {
        if(empty($request->employee_id)){
            throw new ParameterException("Parameter ID pekerja tidak valid.");
        }

        $dataPekerja = $this->employeeRepo->getEmployee(['id'=>$request->employee_id]);
        if(empty($dataPekerja->toArray())){
            throw new DataNotFoundException("Data pekerja tidak ditemukan");
        }

        DB::transaction(function () use ($request){
            $this->employeeRepo->deleteEmployee(strval($request->employee_id));
            $this->profileRepo->deleteProfileEmployee(strval($request->employee_id));
            $this->familyRepo->deleteFamilyEmployee(['employee_id'=>$request->employee_id]);
            $this->educationtRepo->deleteEducationEmployee(['employee_id'=>$request->employee_id]);
        });


        $this->output->responseCode = '00';
        $this->output->responseDesc = 'Delete data berhasil';

        return response()->json($this->output);
    }

    public function reportDataEmployee(Request $request)
    {
        $filterEmployee = [
            'id'        => !empty($request->id) ? $request->id : null,
            'nik'       => !empty($request->nik) ? $request->nik : null,
            'flag_list'  => true
        ];

        $getListEmployee = $this->employeeRepo->getEmployeeJoin($filterEmployee);
        if(empty($getListEmployee)){
            throw new DataNotFoundException("Data pekerja tidak ditemukan.");
        }

        $config = [
            'mode' => 'utf-8',
            'format' => 'A4-L', // A4 landscape
            'default_font_size' => 0,
            'default_font' => '',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 0,
            'margin_footer' => 0,
            'orientation' => 'L' // Landscape
        ];

        // print_r($getListEmployee);die;

        $mpdf = new Mpdf($config);
        $result = view('report_employee', ['data_pdf' => $getListEmployee])->render();
        $mpdf->WriteHTML($result);
        $mpdf->Output();
        exit;
    }
}
