<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompletionForm extends Model
{
  use SoftDeletes;
    protected $table = "completion_form";
    protected $fillable = ["job_id","engineer_name","opening_time","engineer_name", "postcode","completion_time","description","further_work","photo_path","video_path","accepted_technician","customer_present","submitted_time" , "pdf" , "job_type"];
  
//    protected $casts = [
//         'photo_path' => 'array', // Ensure JSON arrays are cast correctly
        
//     ];
}
