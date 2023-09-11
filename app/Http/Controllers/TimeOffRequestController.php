<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\TimeOffRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Notifications\TimeOffRequestNotification;

class TimeOffRequestController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timeOffRequests = TimeOffRequest::orderBy('created_at', 'desc')->get();
        foreach($timeOffRequests as $timeOffRequest){
            $timeOffRequest->applicant;
        }
        if($timeOffRequests){
            return $this->successResponse($timeOffRequests,'Successfully returned time Off requests');
        }else{
            return $this->errorResponse('No time Off Requests exist,kindly get started and create a new Time off request  ',404);
        }
    }


    public function indexSingle($id)
    {
        $timeOffRequest = TimeOffRequest::find($id);

        if($timeOffRequest){
            return $this->successResponse($timeOffRequest,'Successfully returned time Off request');
        }else{
            return $this->errorResponse('No time Off request exist,kindly get started and create a new time off request  ',404);
        }
    }


    public function indexByApplicant($id)
    {
        $timeOffRequests = TimeOffRequest::employee($id)->get();
        foreach($timeOffRequests as $timeOffRequest){
            $timeOffRequest->company;
            $timeOffRequest->actionBy;
        }
        if($timeOffRequests){
            return $this->successResponse($timeOffRequests,'Successfully returned time Off requests');
        }else{
            return $this->errorResponse('No time Off requests exist,kindly get started and create a new time off request  ',404);
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
            'start_date_time'=>'required',
            'is_all_day'=>'required',
            'period'=>'required',
            'note'=>'nullable',
            'applicant_id'=>'required',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
            return  $this->errorResponse($validator->errors());
        }
        
        $endDateTime = "";

        
        if($request->is_all_day=="TRUE"){
            $endDateTime = Carbon::parse($request->start_date_time)->addDays($request->period)->format('Y-m-d H:i:s');
        }else{
            $endDateTime = Carbon::parse($request->start_date_time)->addMinutes($request->period)->Format('Y-m-d H:i:s');
        }

        $employee = Employee::find($request->applicant_id);
        $startDateTime = Carbon::parse($request->start_date_time)->Format('Y-m-d H:i:s');

        $timeOffRequest = TimeOffRequest::create([
            'start_date_time' => $startDateTime,
            'end_date_time' => $endDateTime,
            'is_all_day' => $request->is_all_day,
            'period' => $request->period,
            'note'=>$request->note,
            'applicant_id' => $request->applicant_id,
            'company_id' => $request->company_id,
        ]);

        //Send Notification Email
        \Mail::to($employee->user->email_address)->send(new \App\Mail\AppliedTimeOff($employee->user->first_name.' '.$employee->user->last_name,$request->start_date_time,$endDateTime));
        
        //Send Whatsapp message notification to user notifying of access to account
        $request->user()->notify(new TimeOffRequestNotification($employee->user,$startDateTime,$endDateTime));
          
        return $this->successResponse($timeOffRequest,'Successfully applied for Time Off', Response::HTTP_CREATED);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeOffRequest  $timeOffRequest
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }
        $timeOffRequest = TimeOffRequest::find($request->request_id);
        $timeOffRequest->status = "APPROVED";
        $timeOffRequest->save();

        if($timeOffRequest){
            return $this->successResponse($timeOffRequest,'Successfully Approved time Off request');
        }else{
            return $this->errorResponse('No time Off requests exist,kindly get started and create a new time off request  ',404);
        }
    }

    public function reject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $timeOffRequest = TimeOffRequest::find($request->request_id);
        $timeOffRequest->status = "REJECTED";
        $timeOffRequest->save();

        if($timeOffRequest){
            return $this->successResponse($timeOffRequest,'Successfully Rejected time Off request');
        }else{
            return $this->errorResponse('No time Off requests exist,kindly get started and create a new time off request  ',404);
        }
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TimeOffRequest  $timeOffRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimeOffRequest $timeOffRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeOffRequest  $timeOffRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeOffRequest $timeOffRequest)
    {
        //
    }
}
