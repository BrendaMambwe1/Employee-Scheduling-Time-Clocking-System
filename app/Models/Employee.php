<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

        
    protected $fillable = [
        'agreed_daily_hours',
        'agreed_weekly_hours',
        'employee_type',
        'user_id',
        'position_id',
        'company_id',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id','user_id');
    }

    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id','company_id');
    }

    public function position()
    {
        return $this->hasOne('App\Models\Position', 'id','position_id');
    }

    public function timeOffRequests()
    {
        return $this->hasMany('App\Models\TimeOffRequest', 'employee_id');
    }

    public function shifts()
    {
        return $this->hasMany('App\Models\Shift', 'assigned_to');
    }
}
