<?php

use App\Http\Controllers\V1\ProfileEmployeeController;
use App\Http\Controllers\V1\EducationEmployeeController;
use App\Http\Controllers\V1\FamilyEmployeeController;
use App\Http\Controllers\V1\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function($router){

    // EmployeeController
    $router->post('/inquiryEmployee', [EmployeeController::class, 'inquiryEmployee']);
    $router->post('/inquiryListEmployee', [EmployeeController::class, 'inquiryListEmployee']);
    $router->post('/insertDataEmployee', [EmployeeController::class, 'insertDataEmployee']);
    $router->post('/updateDataEmployee', [EmployeeController::class, 'updateDataEmployee']);
    $router->post('/deleteDataEmployee', [EmployeeController::class, 'deleteDataEmployee']);
    $router->post('/reportDataEmployee', [EmployeeController::class, 'reportDataEmployee']);

    // EducationEmployeeController
    $router->post('/insertDataEducationEmployee', [EducationEmployeeController::class, 'insertDataEducationEmployee']);
    $router->post('/updateDataEducationEmployee', [EducationEmployeeController::class, 'updateDataEducationEmployee']);
    $router->post('/deleteDataEducationEmployee', [EducationEmployeeController::class, 'deleteDataEducationEmployee']);
    
    // ProfileEmployeeController
    $router->post('/insertDataProfileEmployee', [ProfileEmployeeController::class, 'insertDataProfileEmployee']);
    $router->post('/updateDataProfileEmployee', [ProfileEmployeeController::class, 'updateDataProfileEmployee']);
    $router->post('/deleteDataProfileEmployee', [ProfileEmployeeController::class, 'deleteDataProfileEmployee']);
    
    // FamilyEmployeeController
    $router->post('/insertDataFamilyEmployee', [FamilyEmployeeController::class, 'insertDataFamilyEmployee']);
    $router->post('/updateDataFamilyEmployee', [FamilyEmployeeController::class, 'updateDataFamilyEmployee']);
    $router->post('/deleteFamilyEmployee', [FamilyEmployeeController::class, 'deleteFamilyEmployee']);
});

