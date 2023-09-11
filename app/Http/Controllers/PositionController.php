<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;

class PositionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::orderBy('created_at', 'desc')->get();
        foreach($positions as $position){
          $position->company;
        }
        if($positions){
            return $this->successResponse($positions,'Successfully returned positions');
        }else{
            return $this->errorResponse('No positions exist,kindly get started and create a new position ',404);
        }
    }
    
    public function indexSingle($id)
    {
        $position = Position::find($id);
        
        if($position){
            $position->company;
            return $this->successResponse($position,'Successfully returned position');
        }else{
            return $this->errorResponse('No position exist,kindly get started and create a new position ',404);
        }
    }

    public function indexAllCompany($id)
    {
        $positions = Position::where('company_id',$id)->get();
        
        if($positions){
            return $this->successResponse($positions,'Successfully returned positions');
        }else{
            return $this->errorResponse('No positions exist,kindly get started and create a new position ',404);
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
            'work_hours'=>'nullable',
            'breaks'=>'nullable',
            'hourly_rate'=>'nullable',
            'overtime_rate'=>'nullable',
            'leave_rate'=>'nullable',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $department = Position::create([
            'name'=>$request->name,
            'work_hours'=>$request->work_hours,
            'breaks'=>$request->breaks,
            'hourly_rate'=>$request->hourly_rate,
            'overtime_rate'=>$request->overtime_rate,
            'leave_rate'=>$request->leave_rate,
            'company_id'=>$request->company_id,
        ]);
            
        return $this->successResponse($department,'Successfully created position', Response::HTTP_CREATED);
     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'work_hours'=>'nullable',
            'breaks'=>'nullable',
            'hourly_rate'=>'nullable',
            'overtime_rate'=>'nullable',
            'leave_rate'=>'nullable',
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
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        //
    }
}
