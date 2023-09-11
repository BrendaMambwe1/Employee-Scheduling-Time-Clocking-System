<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentManagers extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id',
        'employee_id'
     ];
 
     public function employee()
     {
         return $this->hasOne('App\Models\Employee', 'id','employee_id');
     }
 
     public function department()
     {
         return $this->hasOne('App\Models\Department', 'id','department_id');
     }
}
