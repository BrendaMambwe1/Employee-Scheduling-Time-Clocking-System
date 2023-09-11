<?php

namespace App\Http\Controllers;

use App\Models\Availbility;
use Illuminate\Http\Request;

class AvailbilityController extends Controller
{
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
            'start_date_time'=>'required',
            'end_date_time'=>'required',
            'isRecurring'=>'nullable',
            'employee_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $availbility = Availbility::create([
            'start_date_time' => $request->start_date_time,
            'end_date_time' => $request->end_date_time,
            'isRecurring' => $request->isRecurring,
            'employee_id' => $request->employee_id,
        ]);
            
        return $this->successResponse($availbility,'Successfully created availbility', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Availbility  $availbility
     * @return \Illuminate\Http\Response
     */
    public function show(Availbility $availbility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Availbility  $availbility
     * @return \Illuminate\Http\Response
     */
    public function edit(Availbility $availbility)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Availbility  $availbility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Availbility $availbility)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Availbility  $availbility
     * @return \Illuminate\Http\Response
     */
    public function destroy(Availbility $availbility)
    {
        //
    }
}
