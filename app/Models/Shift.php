<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
       //Table
       protected $table = 'shifts';
    
       //Primary key
       protected $primaryKey = 'id';
   
       //timestamps
       public $timestamps = true;
      
      protected $fillable = [
          'date',
          'isRecurring',
          'recurring_period',
          'scheduled_start_time',
          'scheduled_end_time',
          'longitude',
          'latitude',
          'assigned_to',
          'posted_by',
          'company_id',
          'department_id',
          'status',
          'note',
          'is_acknowledged',
          'hasOvertime',
      ];
      protected $casts = [
        'scheduled_start_time' => 'datetime: H:i',
        'scheduled_end_time' => 'datetime: H:i',
      ]; 

      public function assignedToEmployee()
      {
        return $this->belongsTo('App\Models\Employee', 'assigned_to');
      }

      public function scopeAssignedTo($query,$id)
      {
        return $query->where('assigned_to', '=', $id);
      }

      public function postedBy()
      {
        return $this->belongsTo('App\Models\Employee', 'posted_by');
      }

      public function company()
      {
        return $this->belongsTo('App\Models\Company', 'company_id');
      }

      public function department()
      {
        return $this->belongsTo('App\Models\Department', 'department_id');
      }

      public function timeCard()
      {
        return $this->hasOne('App\Models\TimeCard', 'id','shift_id');
      }

      public function shiftsOppApplications()
      {
        return $this->hasMany('App\Models\ShiftOppApplications', 'shift_id');
      }

      public function tasksShifts()
      {
        return $this->hasMany('App\Models\TaskShift', 'shift_id');
      }

}
