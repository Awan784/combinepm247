<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investigation extends Model
{
  use SoftDeletes;
    protected $table = "investigations";
    protected $fillable = ["job_id","engineer_id","engineer_name","start_time","end_time","postcode","job_type","estimate_time","estimate_materials","problem_reported","	problem_location","rectify_needed","notes","photo_path","video_path","accepted_at"];
  
//    protected $casts = [
//         'photo_path' => 'array', // Ensure JSON arrays are cast correctly
        
//     ];
}
