<?php

namespace App\Http\Controllers;

use App\Models\DepartmentManagers;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Validator;
use Illuminate\Http\Response;

class DepartmentManagersController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'employee_id'=>'required',
            'department_id'=>'required',
        ]); 

        if($validator->fails()){
            return  $this->errorResponse($validator->errors());
        }
        
        //Check if the Employee id provided actually exists
        if(!Employee::find($request->employee_id)){
            return  $this->errorResponse('Employee id provided doesn\'t  exist');
        }

        //Check if the Department id provided actually exists
        if(!Department::find($request->department_id)){
            return  $this->errorResponse('Department id provided doesn\'t exist');
        }

        //Checking if the Employee already a manager to this desired department
        if(DepartmentManagers::where([['department_id',$request->department_id],['employee_id',$request->employee_id]])->first()){
            return  $this->errorResponse('Employee already is a manager of this department');
        }

        $department = DepartmentManagers::create([
            'department_id' => $request->department_id,
            'employee_id' => $request->employee_id,
        ]);
            
        return $this->successResponse($department,'Successfully added employee as a manager of named department', Response::HTTP_CREATED);
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DepartmentManagers  $departmentManagers
     * @return \Illuminate\Http\Response
     */
    public function show(DepartmentManagers $departmentManagers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DepartmentManagers  $departmentManagers
     * @return \Illuminate\Http\Response
     */
    public function edit(DepartmentManagers $departmentManagers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DepartmentManagers  $departmentManagers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DepartmentManagers $departmentManagers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DepartmentManagers  $departmentManagers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = DepartmentManagers::find($id);

        if($department){
            $department->delete(); 
            return $this->successResponse($department,'Successfully revoked user from department manger', Response::HTTP_CREATED);
        }else{
            return  $this->errorResponse('Provided department Id of '.$id .' Does not exist');
        }
    }
}
