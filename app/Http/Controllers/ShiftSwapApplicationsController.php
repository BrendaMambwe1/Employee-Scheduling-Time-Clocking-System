<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\ShiftSwapApplications;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;

class ShiftSwapApplicationsController extends Controller
{
    //Accepting of Shift swap regardless of time difference
    //Provided there isnt an overlap with another shift
    //Position being a factor?
    
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShiftSwapApplications  $shiftSwapApplications
     * @return \Illuminate\Http\Response
     */
    public function show(ShiftSwapApplications $shiftSwapApplications)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShiftSwapApplications  $shiftSwapApplications
     * @return \Illuminate\Http\Response
     */
    public function edit(ShiftSwapApplications $shiftSwapApplications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShiftSwapApplications  $shiftSwapApplications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShiftSwapApplications $shiftSwapApplications)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShiftSwapApplications  $shiftSwapApplications
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShiftSwapApplications $shiftSwapApplications)
    {
        //
    }
}
