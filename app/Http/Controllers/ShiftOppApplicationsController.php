<?php

namespace App\Http\Controllers;
use Validator;
use Carbon\Carbon;
use App\Models\ShiftOppApplications;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;

class ShiftOppApplicationsController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $shifts = ShiftOppApplications::where([['department_id',$id],['created_at','>=',Carbon::today()->toDateString()]])->orderBy('created_at', 'desc')->get();
        foreach($shifts as $shift){
            $shift->shift;
        }
        if($shifts){
            return $this->successResponse($shifts,'Successfully returned shifts');
        }else{
            return $this->errorResponse('No shifts exist,kindly get started and create a new shifts  ',404);
        }
    }

    public function indexEmployee($id)
    {
        $shifts = ShiftOppApplications::where([['applicant_id',$id],['created_at','>=',Carbon::today()->toDateString()]])->orderBy('created_at', 'desc')->get();
        foreach($shifts as $shift){
            $shift->shift;
        }
        if($shifts){
            return $this->successResponse($shifts,'Successfully returned shifts');
        }else{
            return $this->errorResponse('No shifts exist,kindly get started and create a new shifts  ',404);
        }
    }

    public function indexSingle($id)
    {
        $shift = ShiftOppApplications::find($id);

        if($shift){
            $shift->shift;
            return $this->successResponse($shift ,'Successfully returned shift');
        }else{
            return $this->errorResponse('No shift application exist with the provided id,kindly get started and create a new shift',404);
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
            'shift_id'=>'required',
            'applicant_id'=>'required',
            'department_id'=>'required',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
            return  $this->errorResponse($validator->errors());
        }

        $isShiftAvailable = $this->isShiftAvailable($request->shift_id);
        $hasShiftPending = $this->hasShiftPending($request->shift_id,$request->applicant_id);

        if($isShiftAvailable){
            if(!$hasShiftPending){
                $shift = ShiftOppApplications::create([
                    'shift_id' => $request->shift_id,
                    'applicant_id' => $request->applicant_id,
                    'department_id' => $request->department_id,
                    'company_id'=>$request->company_id,
                ]);

                return $this->successResponse($shift,'Successfully Applied for a shift', Response::HTTP_CREATED);
            } else{
                return $this->errorResponse('you already have this shift as a pending application wait for action before you can apply again');
            }   
        }else{
            return $this->errorResponse('Shift is not available,it is either taken or nolonger exists');
        }
    }

    public function approve(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shift_application_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }
        $shiftApplication = ShiftOppApplications::find($request->shift_application_id);

        if($shiftApplication){

            $shift_id = $shiftApplication->shift_id;
            //Alter shift by adding new applicant ID and change status to | UNAVAILABLE |
            $shift = Shift::find($shift_id);
            $shift->update(array('status'=>'UNAVAILABLE','assigned_to'=>$shiftApplication->applicant_id));
            $shift->save();

            $shiftApplication->status = "APPROVED";
            $shiftApplication->save();

            return $this->successResponse($shiftApplication,'Successfully Approved shift application');
        }else{
            return $this->errorResponse('No shift application exists with the provided ID  ',404);
        }
    }

    public function reject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shift_application_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $shift = ShiftOppApplications::find($request->shift_application_id);
        $shift->status = "REJECTED";
        $shift->save();

        if($shift){
            return $this->successResponse($shift,'Successfully Rejected shift application');
        }else{
            return $this->errorResponse('No shift application exists with the provided ID   ',404);
        }
    }


    public function isShiftAvailable($id){
        $shift = Shift::find($id);
        if($shift){
           if($shift->status=="AVAILABLE"){
               return True;
           }else{
               return False;
           }
        }else{
            return False;
        }
    }

    public function hasShiftPending($shift_id,$applicant_id){
        $shift = ShiftOppApplications::where([['shift_id',$shift_id],['applicant_id',$applicant_id],['status','PENDING']])->first();
        if($shift){
           return True;
        }else{
           return False;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShiftOppApplications  $shiftOppApplications
     * @return \Illuminate\Http\Response
     */
    public function edit(ShiftOppApplications $shiftOppApplications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShiftOppApplications  $shiftOppApplications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShiftOppApplications $shiftOppApplications)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShiftOppApplications  $shiftOppApplications
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShiftOppApplications $shiftOppApplications)
    {
        //
    }
}
