<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Validator;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::orderBy('created_at', 'desc')->get();
        foreach($companies as $company){
            $company->departments;
          }
        if($companies){
            return $this->successResponse($companies,'Successfully returned companies');
        }else{
            return $this->errorResponse('No companies exist,kindly get started and create a new company ',404);
        }
        
    }

    public function indexActive()
    {
        $companies = Company::orderBy('created_at', 'desc')->where('status','ACTIVE')->get();
        foreach($companies as $company){
            $company->departments;
          }
        if($companies){
            return $this->successResponse($companies,'Successfully returned companies');
        }else{
            return $this->errorResponse('No companies exist,kindly get started and create a new company ',404);
        }
        
    }
    
    public function indexInActive()
    {
        $companies = Company::orderBy('created_at', 'desc')->where('status','INACTIVE')->get();
        foreach($companies as $company){
            $company->departments;
          }
        if($companies){
            return $this->successResponse($companies,'Successfully returned companies');
        }else{
            return $this->errorResponse('No companies exist,kindly get started and create a new company ',404);
        }
        
    }

    public function indexSingle($id)
    {
        $company = Company::find($id);
        
        if($company){
            $company->departments;
            return $this->successResponse($company,'Successfully returned company');
        }else{
            return $this->errorResponse('No company exists with id '. $id ,404);
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
            'address'=>'required',
            'number_of_employees'=>'required',
            'start_day_of_week'=>'nullable',
            'sector'=>'nullable',
            'currency'=>'nullable',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $company = Company::create([
            'name' => $request->name,
            'address' => $request->address,
            'number_of_employees' => $request->number_of_employees,
            'start_day_of_week'=>$request->start_day_of_week,
            'sector' => $request->sector,
            'currency' => $request->currency,
        ]);
            
        return $this->successResponse($company,'Successfully created company', Response::HTTP_CREATED);
        
    }

    public function inactiveCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }
        $company = Company::find($request->id);
        $company->status = 'INACTIVE';
        $company->save();
        if($company){
            return $this->successResponse($company,'Successfully Disable this company');
        }else{
            return $this->errorResponse('No company exists with id '. $id ,404);
        }
        
    }
    public function activateCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }
        $company = Company::find($request->id);
        $company->status = 'ACTIVE';
        $company->save();
        if($company){
            return $this->successResponse($company,'Successfully Activated this company');
        }else{
            return $this->errorResponse('No company exists with id '. $id ,404);
        }
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'address'=>'required',
            'number_of_employees'=>'required',
            'start_day_of_week'=>'nullable',
            'sector'=>'nullable',
            'currency'=>'nullable',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $company = Company::find($id);
        
        if($company == null){
            return  $this->errorResponse('Provided company Id of '.$id .' Does not exist');
        }

        $company->fill($request->all());

        if($company->isClean()) {
            return $this->errorResponse('At least one value must change to update company',
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $company->save();

        return $this->successResponse($company,'Successfully edited Company', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
