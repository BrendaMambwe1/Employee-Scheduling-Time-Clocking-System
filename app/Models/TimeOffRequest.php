<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeOffRequest extends Model
{
    use HasFactory;

        
     //Primary key
     protected $primaryKey = 'id';
 
     //timestamps
     public $timestamps = true;
    
    protected $fillable = [
        'start_date_time',
        'end_date_time',
        'is_all_day',
        'period',
        'note',
        'status',
        'applicant_id',
        'action_by',
        'company_id',
    ];

    public function applicant()
    {
        return $this->belongsTo('App\Models\Employee', 'applicant_id');
    }
    
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function actionBy()
    {
        return $this->belongsTo('App\Models\Employee', 'applicant_id');
    }

    public function scopeStatus($query,$status)
    {
        return $query->where('status', '=', $status);
    }

    public function scopeEmployee($query,$id)
    {
        return $query->where('applicant_id', '=', $id);
    }
}
