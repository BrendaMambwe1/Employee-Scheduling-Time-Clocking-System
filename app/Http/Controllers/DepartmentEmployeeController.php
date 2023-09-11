<?php

namespace App\Http\Controllers;

use App\Models\DepartmentEmployee;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Validator;
use Illuminate\Http\Response;

class DepartmentEmployeeController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'=>'required',
        ]); 

        if($validator->fails()){
            return  $this->errorResponse($validator->errors());
        }
        
        //Check if the Employee id provided actually exists
        if(!Employee::find($request->employee_id)){
            return  $this->errorResponse('Employee id provided doesn\'t  exist');
        }

        //Check if the Department id provided actually exists
        if(!Department::find($id)){
            return  $this->errorResponse('Department id provided doesn\'t exist');
        }

        //Checking if the Employee already belongs to this desired department
        if(DepartmentEmployee::where([['department_id',$id],['employee_id',$request->employee_id]])->first()){
            return  $this->errorResponse('Employee already belongs to this department');
        }

        $department = DepartmentEmployee::create([
            'department_id' => $id,
            'employee_id' => $request->employee_id,
        ]);
            
        return $this->successResponse($department,'Successfully added employee to department', Response::HTTP_CREATED);
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DepartmentEmployee  $departmentEmployee
     * @return \Illuminate\Http\Response
     */
    public function show(DepartmentEmployee $departmentEmployee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DepartmentEmployee  $departmentEmployee
     * @return \Illuminate\Http\Response
     */
    public function edit(DepartmentEmployee $departmentEmployee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DepartmentEmployee  $departmentEmployee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DepartmentEmployee $departmentEmployee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DepartmentEmployee  $departmentEmployee
     * @return \Illuminate\Http\Response
     */
    public function destroy(DepartmentEmployee $departmentEmployee)
    {
        //
    }
}
