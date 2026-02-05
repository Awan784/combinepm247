<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionsModel extends Model
{
    public $table = "permissions";

    protected $fillable = ["permission_id","module_id","user_id"];

    const READ = 1;
    const WRITE = 2;
    const FULLACCESS = 3;
}
