<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponser;
use App\Models\Department;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::orderBy('created_at', 'desc')->get();
        foreach($departments as $department){
          $department->company;
        }
        if($departments){
            return $this->successResponse($departments,'Successfully returned departments');
        }else{
            return $this->errorResponse('No departments exist,kindly get started and create a new department ',404);
        }
    }

    public function indexSingle($id)
    {
        $department = Department::find($id);
        if($department){
            $department->company;
            return $this->successResponse($department,'Successfully returned department');
        }else{
            return $this->errorResponse('No department exists with id '. $id ,404);
        }
        
    }

    public function indexAllCompanyDepartments($id)
    {
        $departments = Department::where('company_id',$id)->get();

        if($departments){
            return $this->successResponse($departments,'Successfully returned departments');
        }else{
            return $this->errorResponse('No department exists with id '. $id ,404);
        }
        
    }

    public function indexAllDepartmentEmployees($id)
    {
        $department = Department::find($id);

        if($department){
            $department->employeeDepartments;
            return $this->successResponse($department,'Successfully returned department with all its employees');
        }else{
            return $this->errorResponse('No department exists with id '. $id ,404);
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
            'name'=>'required',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $department = Department::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
        ]);
            
        return $this->successResponse($department,'Successfully created department', Response::HTTP_CREATED);
     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $department = Department::find($id);

        if($department == null){
            return  $this->errorResponse('Provided department Id of '.$id .' Does not exist');
        }

        $department->fill($request->all());

        if($department->isClean()) {
            return $this->errorResponse('At least one value must change to update department',
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $department->save();

        return $this->successResponse($department,'Successfully edited department', Response::HTTP_CREATED);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        //
    }
}
