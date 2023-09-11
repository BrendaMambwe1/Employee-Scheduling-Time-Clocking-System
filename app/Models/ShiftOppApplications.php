<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftOppApplications extends Model
{
    use HasFactory;

    protected $table = 'shift_opp_applications';
    
    //Primary key
    protected $primaryKey = 'id';

    //timestamps
    public $timestamps = true;
   
   protected $fillable = [
       'shift_id',
       'status',
       'applicant_id',
       'action_by',
       'company_id',
       'department_id',
   ];
   public function shift()
   {
       return $this->belongsTo('App\Models\Shift', 'shift_id');
   }

   public function department()
   {
       return $this->belongsTo('App\Models\Department', 'department_id');
   }

   public function company()
   {
       return $this->belongsTo('App\Models\Company', 'company_id');
   }

   public function applicant()
   {
       return $this->belongsTo('App\Models\Employee', 'applicant_id');
   }

   public function actionedBy()
   {
       return $this->belongsTo('App\Models\Employee', 'action_by');
   }


}
