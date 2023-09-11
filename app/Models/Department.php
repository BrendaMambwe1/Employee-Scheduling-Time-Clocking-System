<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
      //Table
      protected $table = 'departments';

      //Primary key
      protected $primaryKey = 'id';
  
      //timestamps
      public $timestamps = true;
 
      protected $fillable = [
         'name',
         'company_id'
      ];

      public function company()
      {
          return $this->belongsTo('App\Models\Company', 'company_id');
      }

      public function employeeDepartments()
      {
          return $this->hasMany('App\Models\DepartmentEmployee', 'department_id')->with('employee');
      }

      public function managersDepartment()
      {
          return $this->hasMany('App\Models\DepartmentManagers', 'department_id')->with('employee');
      }
}
