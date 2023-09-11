<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DepartmentEmployee extends Model
{
    use HasFactory;

    protected $table = 'department_employees';

      //Primary key
      protected $primaryKey = 'employee_id';

    public $timestamps = false;

    //set auto increment to false
    public $incrementing = false;

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
