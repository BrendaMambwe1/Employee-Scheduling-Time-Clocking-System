<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'task_description',
        'created_by',
        'department_id',
    ];

    public function department()
    {
      return $this->belongsTo('App\Models\Department', 'department_id');
    }
    
    public function createdBy()
    {
      return $this->belongsTo('App\Models\Employee', 'created_by');
    }
}

