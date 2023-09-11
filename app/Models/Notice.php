<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'expirational_date',
        'audience',
        'created_by',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
    public function createdBy()
   {
       return $this->belongsTo('App\Models\Employee', 'created_by');
   }
}
