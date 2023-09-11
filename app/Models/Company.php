<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

     //Table
     protected $table = 'companies';

     //Primary key
     protected $primaryKey = 'id';
 
     //timestamps
     public $timestamps = true;

     protected $fillable = [
        'name',
        'address',
        'number_of_employees',
        'start_day_of_week',
        'sector',
        'currency',
        'status'
    ];
    

    public function positions()
    {
        return $this->hasMany('App\Models\Position', 'company_id');
    }
    
    public function departments()
    {
        return $this->hasMany('App\Models\Department', 'company_id');
    }
        
    public function employees()
    {
        return $this->hasMany('App\Models\Employee', 'company_id')->with('user');
    }
}
