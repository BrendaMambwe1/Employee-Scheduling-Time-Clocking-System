<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availbility extends Model
{
    use HasFactory;

    protected $fillable = [
        'isRecurring',
        'start_date_time',
        'end_date_time',
        'employee_id',
    ];

    public function employee()
    {
      return $this->belongsTo('App\Models\Employee', 'employee_id');
    }
}
