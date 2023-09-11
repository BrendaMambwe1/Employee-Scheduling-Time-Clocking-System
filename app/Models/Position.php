<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    //Table
    protected $table = 'positions';
    
    //Primary key
    protected $primaryKey = 'id';
 
    //timestamps
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'work_hours',
        'breaks',
        'hourly_rate',
        'overtime_rate',
        'leave_rate',
        'company_id',
    ];
    
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }



}
