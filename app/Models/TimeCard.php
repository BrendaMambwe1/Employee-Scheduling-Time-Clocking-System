<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeCard extends Model
{
    use HasFactory;
     //Table
     protected $table = 'time_cards';
    
     //Primary key
     protected $primaryKey = 'id';
 
     //timestamps
     public $timestamps = true;
    
    protected $fillable = [
        'shift_id',
        'actual_start_time',
        'actual_end_time',
        'status',
        'standing',
    ];

    public function shift()
    {
        return $this->belongsTo('App\Models\Shift', 'shift_id');
    }
}
