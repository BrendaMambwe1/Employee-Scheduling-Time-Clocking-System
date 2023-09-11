<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Tasks;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;

class TasksController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Tasks::orderBy('created_at', 'desc')->get();
        if($tasks){
            foreach($tasks as $task){
                $task->createdBy->user;
                $task->department;
             }
            return $this->successResponse($tasks,'Successfully returned tasks');
        }else{
            return $this->errorResponse('No tasks exist,kindly get started and create a new task ',404);
        }
    }

    
    public function indexAllByDepartment($id)
    {
        $tasks = Tasks::where('department_id',$id)->get();        
        if($tasks){
            foreach($tasks as $task){
                $task->createdBy->user;
             }
            return $this->successResponse($tasks,'Successfully returned tasks');
        }else{
            return $this->errorResponse('No tasks exist,kindly get started and create a new task ',404);
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
            'task_name'=>'nullable',
            'task_description'=>'required',
            'created_by'=>'required',
            'department_id'=>'nullable',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $task = Tasks::create([
            'task_name'=>$request->task_name,
            'task_description'=>$request->task_description,
            'created_by'=>$request->created_by,
            'department_id'=>$request->department_id,
        ]);
            
        return $this->successResponse($task,'Successfully created task', Response::HTTP_CREATED);
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function show(Tasks $tasks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function edit(Tasks $tasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'task_name'=>'nullable',
            'task_description'=>'required',
            'created_by'=>'required',
            'department_id'=>'nullable',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }

        $task = Tasks::find($id);

        if($task == null){
            return  $this->errorResponse('Provided task Id of '.$id .' Does not exist');
        }

        $task->fill($request->all());

        if($task->isClean()) {
            return $this->errorResponse('At least one value must change to update task',
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $task->save();

        return $this->successResponse($department,'Successfully edited task', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Tasks::find($id);

        if($task){
            $task->delete(); 
            return $this->successResponse($task,'Successfully deleted task', Response::HTTP_CREATED);
        }else{
            return  $this->errorResponse('Provided task Id of '.$id .' Does not exist');
        }
    }
}
