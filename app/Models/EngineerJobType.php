<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EngineerJobType extends Model
{
    protected $table = "engineer_job_types";
    protected $fillable = ["engineer_id","job_type_id"];

    public function engineer(){
        return $this->belongsTo(EngineerModel::class,"engineer_id");
    }

    public function jobtype(){
        return $this->belongsTo(JobType::class,"job_type_id");
    }
}
