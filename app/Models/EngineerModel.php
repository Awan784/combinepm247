<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EngineerModel extends Model
{
    use SoftDeletes;
    protected $table = "engineers";
    protected $fillable = ["name","rating","user_id","postal_codes","home_postcode","lat","long"];

    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function jobTypes(){
        return $this->hasMany(EngineerJobType::class,"engineer_id");
    }
  
  public function jobTypeDetails()
{
    return $this->belongsToMany(JobType::class, 'engineer_job_types', 'engineer_id', 'job_type_id');
}


    public function availability(){
        return $this->hasMany(EngineerAvailability::class,"engineer_id");
    }

    public function todayAvailablity(){
       $availability =  EngineerAvailability::where("date_start",date("Y-m-d"))->where("engineer_id",$this->id)->first();
       return $availability;
    }
}
