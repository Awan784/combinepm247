<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EngineerAvailability extends Model
{
    public $table = "engineer_availability";
    protected $fillable = [
        "engineer_id",
        "date_start",
        "start_time",
        "end_time",
        "title"
    ];

    public function engineer(){
        return $this->belongsTo(EngineerModel::class,"engineer_id")->withTrashed();
    }
}
