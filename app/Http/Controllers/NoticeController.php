<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Notice;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;

class NoticeController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = Notice::orderBy('created_at', 'desc')->get();

        foreach($notices as $notice){
           $notice->createdBy->user;
        }
       
        if($notices){
            return $this->successResponse($notices,'Successfully returned notices');
        }else{
            return $this->errorResponse('No notices exist,kindly get started and create a new notice ',404);
        }
    }

    public function indexAllByCompany($id)
    {
        $notices = Notice::where('company_id',$id)->get();

        foreach($notices as $notice){
            $notice->createdBy->user;
         }
        
        if($notices){
            return $this->successResponse($notices,'Successfully returned notices');
        }else{
            return $this->errorResponse('No notices exist,kindly get started and create a new position ',404);
        }
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
            'title'=>'nullable',
            'message'=>'required',
            'expirational_date'=>'required',
            'audience'=>'nullable',
            'created_by'=>'required',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $notice = Notice::create([
            'title'=>$request->title,
            'message'=>$request->message,
            'expirational_date'=>$request->expirational_date,
            'audience'=>$request->audience,
            'created_by'=>$request->created_by,
            'company_id'=>$request->company_id,
        ]);
            
        return $this->successResponse($notice,'Successfully created notice', Response::HTTP_CREATED);
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'nullable',
            'message'=>'required',
            'expirational_date'=>'required',
            'audience'=>'nullable',
            'created_by'=>'required',
            'company_id'=>'required',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $notice = Notice::find($id);

        if($notice == null){
            return  $this->errorResponse('Provided notice Id of '.$id .' Does not exist');
        }

        $notice->fill($request->all());

        if($notice->isClean()) {
            return $this->errorResponse('At least one value must change to update notice',
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $notice->save();

        return $this->successResponse($department,'Successfully edited notice', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notice $notice)
    {
        //
    }
}
