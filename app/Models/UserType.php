<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
   protected $table = "user_types";
   protected $fillable = ["id"];

   public function users(){
      return $this->hasMany(User::class,"user_type_id");
   }

   const ADMIN = 1;
   const SYSTEMUSER = 2;
   const ENGINEER = 3;
}
