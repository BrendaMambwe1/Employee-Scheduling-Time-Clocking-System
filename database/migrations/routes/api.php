<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthControllers\PassportAuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentEmployeeController;
use App\Http\Controllers\TimeOffRequestController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftOppApplicationsController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\DepartmentManagersController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('isWorking', [PassportAuthController::class, 'test']);

Route::post('register', [PassportAuthController::class, 'register']);

Route::post('login', [PassportAuthController::class, 'login']);


Route::group(['prefix'=>'admin','middleware' => ['auth:api']], function(){
    Route::post('shift-re-assign',[ShiftController::class, 'reassignShift']);
});

Route::group(['prefix'=>'v1','middleware' => ['auth:api']], function(){  

    Route::get('userInfo', [PassportAuthController::class, 'userInfo']);
    Route::get('users', [PassportAuthController::class, 'users']);
 
    //Company
    Route::get('companies', [CompanyController::class, 'index']);
    Route::get('companies-active', [CompanyController::class, 'indexActive']);
    Route::get('companies-inactive', [CompanyController::class, 'indexInActive']);
    Route::get('company/{id}', [CompanyController::class, 'indexSingle']);
    Route::post('company-disable', [CompanyController::class, 'inactiveCompany']);
    Route::post('company-enable', [CompanyController::class, 'activateCompany']);
    Route::post('company',[CompanyController::class, 'store']);
    Route::patch('company/{id}',[CompanyController::class, 'update']);
    
    //Department
    Route::get('departments', [DepartmentController::class, 'index']);
    Route::get('department/{id}', [DepartmentController::class, 'indexSingle']);
    Route::get('departments-company/{id}', [DepartmentController::class, 'indexAllCompanyDepartments']);
    Route::get('department-employees/{id}', [DepartmentController::class, 'indexAllDepartmentEmployees']);
    Route::post('department-manager',[DepartmentManagersController::class,'store']);
    Route::post('department',[DepartmentController::class, 'store']);
    Route::patch('department/{id}',[DepartmentController::class, 'update']);
  
    //Department-Employee
    Route::post('department-employee/{id}',[DepartmentEmployeeController::class, 'store']);

    //Position
    Route::get('positions', [PositionController::class, 'index']);
    Route::get('position/{id}', [PositionController::class, 'indexSingle']);
    Route::get('positions-company/{id}', [PositionController::class, 'indexAllCompany']);
    Route::post('position',[PositionController::class, 'store']);
    Route::patch('position/{id}',[PositionController::class, 'update']);
    
    //Notice
    Route::get('notices', [NoticeController::class, 'index']);
    Route::get('notices-company/{id}', [NoticeController::class, 'indexAllByCompany']);
    Route::post('notice',[NoticeController::class, 'store']);
    Route::patch('notice/{id}',[NoticeController::class, 'update']);
  
     //Tasks
     Route::get('tasks', [TasksController::class, 'index']);
     Route::get('tasks-department/{id}', [TasksController::class, 'indexAllByDepartment']);
     Route::post('task',[TasksController::class, 'store']);
     Route::patch('task/{id}',[TasksController::class, 'update']);
     Route::delete('task-delete/{id}',[TasksController::class, 'destroy']);
   
    //Employee
    Route::get('employee/{id}', [EmployeeController::class, 'indexSingle']);
    Route::get('employees/{id}', [EmployeeController::class, 'index']);
    Route::get('employee-network/{id}', [EmployeeController::class, 'network']);
    Route::get('employees-company/{id}', [EmployeeController::class, 'indexEmployeesCompany']);
    Route::post('employee',[EmployeeController::class, 'store']);

    //TimeOffRequests
    Route::get('timeoffrequest/{id}', [TimeOffRequestController::class, 'indexSingle']);
    Route::post('timeoffrequest-approve', [TimeOffRequestController::class, 'approve']);
    Route::post('timeoffrequest-reject', [TimeOffRequestController::class, 'reject']);
    Route::get('timeoffrequests', [TimeOffRequestController::class, 'index']);
    Route::get('timeoffrequests-applicant/{id}', [TimeOffRequestController::class, 'indexByApplicant']);
    Route::post('timeoffrequest',[TimeOffRequestController::class, 'store']);



    
    //Shift
    Route::get('shift/{id}', [ShiftController::class, 'indexSingle']);
    Route::get('shifts-assigned/{id}', [ShiftController::class, 'indexByAssignedTo']);
    Route::post('shift',[ShiftController::class, 'store']);
    Route::get('shifts/{id}', [ShiftController::class, 'index']);
    Route::get('shifts-available/{id} ', [ShiftController::class, 'indexAvailable']);
    Route::get('shifts-unavailable/{id} ', [ShiftController::class, 'indexUnAvailable']);
    Route::get('shift-applications/{id} ', [ShiftOppApplicationsController::class, 'index']);
    Route::get('shift-application-by-employee/{id} ', [ShiftOppApplicationsController::class, 'indexEmployee']);
    Route::get('shift-history/{id}', [ShiftController::class, 'indexHistory']);
    Route::post('shift-drop',[ShiftController::class, 'dropShift']);
    Route::post('shift-apply',[ShiftOppApplicationsController::class, 'store']);
    Route::post('shift-approve', [ShiftOppApplicationsController::class, 'approve']);
    Route::post('shift-reject', [ShiftOppApplicationsController::class, 'reject']);
    Route::post('shift-acknowledge', [ShiftController::class, 'acknowledgeShift']);
    Route::post('shifts-filter-date', [ShiftController::class, 'filterByDate']);
 });

