<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $table = "jobtypes";
    protected $fillable = ["title", "bgcolor"];


    public function engineers(){
        return $this->hasMany(EngineerJobType::class,"job_type_id");
    }

    public function engineersAvailableToday(){
        $engineers = $this->engineers;
        $count = 0;
        $alreadyCountedEngineer = [];
        foreach($engineers as $engineer){
            if ($engineer->engineer) {
                if(!in_array($engineer->engineer->id,$alreadyCountedEngineer)){
    
                    if($engineer->engineer->todayAvailablity()){
                        $count++;
                    }
                    $alreadyCountedEngineer[] = $engineer->engineer->id;
                }
            }
        }

        return $count;

    }
}
