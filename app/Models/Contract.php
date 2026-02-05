<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    public $table = "contracts";
    protected $fillable = ["job_id","sent_time","received_time", "inform_time", "sent_by", "status"];
    
    public function job(){
        return $this->belongsTo(Job::class,"job_id");
    }
    public function sent_by_user(){
        return $this->belongsTo(User::class,"sent_by");
    }
}
