<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id',
        'task_id'
     ];
 
     public function shift()
     {
        return $this->hasOne('App\Models\Shift', 'id','shift_id');
     }
 
     public function task()
     {
        return $this->hasOne('App\Models\Task', 'id','task_id');
     }
}
