<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Validator;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $employees = Employee::where('company_id',$id)->orderBy('created_at', 'desc')->get();

        if($employees){
            foreach($employees as $employee){
                $employee->user;
                $employee->position;
            }
            return $this->successResponse($employees,'Successfully returned employees');
        }else{
            return $this->errorResponse('No employees exist,kindly get started and create a new employee ',404);
        }
        
    }

    public function indexSingle($id)
    {
        $employee = Employee::find($id);

        if($employee){
            $employee->user;
            $employee->position;
            $employee->company;
            return $this->successResponse($employee,'Successfully returned employee');
        }else{
            return $this->errorResponse('No employee exist,kindly get started and create a new employee ',404);
        }
    }

    public function network($id){
        
        $user = User::find($id);
        $companyList = array();
        $companyEmployeeList = array();

        foreach($user->employeeInfo as $employeeinfo){
           array_push($companyList,$employeeinfo['company']);
        }
        foreach($companyList as $company){
            array_push($companyEmployeeList,$company->load('employees'));
         }
        

        if($user){
            return $this->successResponse($companyEmployeeList,'Successfully returned employee network');
        }else{
            return $this->errorResponse('employee has no network currently ',404);
        }

    }


    public function indexEmployeesCompany($id)
    {
        $employees = Employee::where('company_id',$id)->get();
        if($employees){
            foreach($employees as $employee){
                $employee->user;
                $employee->position;
              }
            return $this->successResponse($employees,'Successfully returned employees');
        }else{
            return $this->errorResponse('No employees exist,kindly get started and create a new employees',404);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agreed_daily_hours'=>'required',
            'agreed_weekly_hours'=>'nullable',
            'employee_type'=>'nullable',
            'user_id'=>'required',
            'position_id'=>'required',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

      

        $employee = Employee::create([
            'agreed_daily_hours'=>$request->agreed_daily_hours,
            'agreed_weekly_hours'=>$request->agreed_weekly_hours,
            'employee_type'=>$request->employee_type,
            'user_id'=>$request->user_id,
            'position_id'=>$request->position_id,
            'company_id'=>$request->company_id,
        ]);
        
        $employee->user;
        $employee->position;
        $employee->company;

        return $this->successResponse($employee,'Successfully created position', Response::HTTP_CREATED);
     
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'agreed_daily_hours'=>'required',
            'agreed_weekly_hours'=>'nullable',
            'employee_type'=>'nullable',
            'user_id'=>'required',
            'position_id'=>'required',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $position = Position::find($id);

        if($position == null){
            return  $this->errorResponse('Provided position Id of '.$id .' Does not exist');
        }

        $position->fill($request->all());

        if($position->isClean()) {
            return $this->errorResponse('At least one value must change to update position',
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $position->save();

        return $this->successResponse($position,'Successfully edited position', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
