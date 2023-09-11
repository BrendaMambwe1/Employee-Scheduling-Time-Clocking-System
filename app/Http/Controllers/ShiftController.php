<?php

namespace App\Http\Controllers;
use Validator;
use DB;
use Carbon\Carbon;
use App\Models\Shift;
use App\Models\TimeCard;
use App\Models\Employee;
use App\Models\TimeOffRequest;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;

class ShiftController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $shifts = Shift::where([['department_id',$id],['date','>=',Carbon::today()->toDateString()]])->orderBy('created_at', 'desc')->get();

        foreach($shifts as $shift){
            $shift->company;
            $shift->department;
            $shift->assignedToEmployee;
        }
        if($shifts){
            return $this->successResponse($shifts,'Successfully returned shifts');
        }else{
            return $this->errorResponse('No shifts exist,kindly get started and create a new shifts  ',404);
        }
    }

    public function indexSingle($id)
    {
        $shift = Shift::find($id);

        if($shift ){
            $shift->company;
            $shift->department;
            $shift->assignedToEmployee;
            return $this->successResponse($shift ,'Successfully returned shift');
        }else{
            return $this->errorResponse('No shift time Off request exist with the provided id,kindly get started and create a new shift',404);
        }
    }

    public function indexByAssignedTo($id)
    {
        $shifts = Shift::assignedTo($id)->where([['date','>=',Carbon::today()->toDateString()]])->get();

        foreach($shifts as $shift){
            $shift->company;
            $shift->department;
            $shift->assignedToEmployee;
        }
        if($shifts){
            return $this->successResponse($shifts,'Successfully returned shifts');
        }else{
            return $this->errorResponse('No shifts exist,kindly get started and create a new shift ',404);
        }
    }

    public function filterByDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'=>'required',
            'date'=>'required',
        ]);

        if($validator->fails()){
            return  $this->errorResponse($validator->errors());
        }

        $shifts = Shift::assignedTo($request->employee_id)->where([['date','>=',$request->date]])->get();

        foreach($shifts as $shift){
            $shift->company;
            $shift->department;
            $shift->assignedToEmployee;
        }
        if($shifts){
            return $this->successResponse($shifts,'Successfully returned shifts');
        }else{
            return $this->errorResponse('No shifts exist,kindly get started and create a new shift ',404);
        }
    }

    public function dropShift(Request $request){
        $validator = Validator::make($request->all(), [
            'shift_id'=>'required',
        ]);

        if($validator->fails()){
            return  $this->errorResponse($validator->errors());
        }

        $shift = Shift::find($request->shift_id);

        if($shift){

            $shift->assigned_to = null;
            $shift->status = "AVAILABLE";
            $shift->save();

            return $this->successResponse($shift ,'Successfully Dropped shift,Kindly assign the shift to another employee');
        }else{
            return $this->errorResponse('No shift of provided id exists',404);
        }
    }


    public function reassignShift(Request $request){
        $validator = Validator::make($request->all(), [
            'shift_id'=>'required',
            'assigned_to'=>'required'
        ]);

        if($validator->fails()){
            return  $this->errorResponse($validator->errors());
        }

        $shift = Shift::find($request->shift_id);

        if($shift){
            $shift->update(array('status'=>'UNAVAILABLE','assigned_to'=>$request->assigned_to));
            $shift->save();
            return $this->successResponse($shift ,'Successfully reassigned shift');
        }else{
            return $this->errorResponse('No shift of provided id exists',404);
        }
    }

    
    public function acknowledgeShift(Request $request){

        $validator = Validator::make($request->all(), [
            'shift_id'=>'required',
        ]);

        if($validator->fails()){
            return  $this->errorResponse($validator->errors());
        }

        $shift = Shift::find($request->shift_id);

        if($shift){
            if($shift->is_acknowledged == 'TRUE'){
                return $this->errorResponse('Shift already acknowledged',404);
            }
            $shift->is_acknowledged = "TRUE";
            $shift->save();

            return $this->successResponse($shift ,'Successfully acknowledged shift');
        }else{
            return $this->errorResponse('No shift of provided id exists',404);
        }
    }


    public function indexAvailable($id)
    {
        $shifts = Shift::where([['status','AVAILABLE'],['department_id',$id],['date','>=',Carbon::today()->toDateString()]])->orderBy('created_at', 'desc')->get();

        foreach($shifts as $shift){
            $shift->company;
            $shift->department;
            // $shift->assignedToEmployee->user;
        }

        if($shifts){
            return $this->successResponse($shifts,'Successfully returned shifts');
        }else{
            return $this->errorResponse('No shifts exist,kindly get started and create a new shifts  ',404);
        }
    }

    public function indexUnAvailable($id)
    {
        $shifts = Shift::where([['status','UNAVAILABLE'],['department_id',$id],['date','>=',Carbon::today()->toDateString()]])->orderBy('created_at', 'desc')->get();
        foreach($shifts as $shift){
            $shift->company;
            $shift->department;
            $shift->assignedToEmployee->user;
        }    foreach($shifts as $shift){
            $shift->company;
            $shift->department;
            $shift->assignedToEmployee->user;
        }
        if($shifts){
            return $this->successResponse($shifts,'Successfully returned shifts');
        }else{
            return $this->errorResponse('No shifts exist,kindly get started and create a new shifts  ',404);
        }
    }

    public function indexHistory($id)
    {
        $shifts = Shift::where([['department_id',$id]])->orderBy('created_at', 'desc')->get();

        foreach($shifts as $shift){
            $shift->company;
            $shift->department;
            $shift->assignedToEmployee->user;
        }
        if($shifts){
            return $this->successResponse($shifts,'Successfully returned shifts');
        }else{
            return $this->errorResponse('No shifts exist,kindly get started and create a new shifts  ',404);
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
            'date'=>'required',
            'scheduled_start_time'=>'required',
            'scheduled_end_time'=>'required',
            'assigned_to'=>'nullable',
            'posted_by'=>'required',
            'department_id'=>'required',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
            return  $this->errorResponse($validator->errors());
        }

        $occuppied = false;
        $status = "UNAVAILABLE";

        if($request->assigned_to == null){
          $status = "AVAILABLE";
          $assignedTo = null;
        }else{
          $assignedTo = $request->assigned_to;
        }

        //Checking if this shift is assigned and belongs to a worker
        if($status == "UNAVAILABLE"){
            //Check if useris available during this period
            
            //Check if user is already booked for another shift duraing that exact time
            if($this->alreadyOnShift($request->assigned_to,$request->date,$request->scheduled_start_time,$request->scheduled_end_time)){
                return $this->errorResponse('Employee is currently unavailable for this duration as employee is aleady on another shift ',404);
            }
            //Check is user is not on time off
            if($this->isOnTimeOff($request->assigned_to,$request->date)){
                return $this->errorResponse('Employee is currently on time off ',404);
            }
            
        }
        #TODO: Test the date time formatting 
        $shift = Shift::create([
            'date' => Carbon::parse($request->date)->format('Y-m-d H:i:s'),
            'scheduled_start_time' => $request->scheduled_start_time,
            'scheduled_end_time' =>  $request->scheduled_end_time,
            'assigned_to' => $assignedTo,
            'posted_by'=>$request->posted_by,
            'department_id' => $request->department_id,
            'company_id' => $request->company_id,
            'status' => $status,
            'company_id' => $request->company_id,
        ]);

        $timeCard = TimeCard::create([
            'shift_id'=>$shift->id
        ]);
        
        return $this->successResponse($shift,'Successfully created a new shift', Response::HTTP_CREATED);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function edit(Shift $shift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift $shift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shift = Shift::find($id);

        if($shift){
            $shift->delete(); 
            return $this->successResponse($shift,'Successfully deleted shift', Response::HTTP_CREATED);
        }else{
            return  $this->errorResponse('Provided shift Id of '.$id .' Does not exist');
        }
    }

    //Checking if employee has an active timeoff request that is granted or rather approved

    public function isOnTimeOff($employee_id,$date){
        $response = TimeOffRequest::where([['applicant_id',$employee_id],['status','Approved'],['start_date_time','>=',$date],['end_date_time','<=',$date]])->get();
       
        error_log($response);

        if(count($response) > 0){
           return true;
        }
       return false;
    }

    public function alreadyOnShift($employee_id,$date,$start_time,$end_time){

       $shifts = Shift::where([['assigned_to',$employee_id],['date','=',$date]])->where([['scheduled_start_time','>=',$start_time]])->where([['scheduled_end_time','<',$end_time]])->get();
     
       error_log($shifts);

       if(count($shifts) > 0){
         return True;
       }
         return false;
       
    }

    public function isAvailable(){

    }
    

}
