<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{
    public $table = "module";
    protected $fillable = ["title"];


    const ENGINEERSDASHBOARD = 1;
    const CONTRACTSDASHBOARD = 2;
    const ASSIGNSDASHBOARD = 3;
    const JOBS = 4;
    CONST CONTRACTS = 5;
    CONST PAYMENTS = 6;
    CONST ENGINEERS = 7;
    CONST SEARCHENGINEERS = 8;
    CONST USERS = 9;
}
