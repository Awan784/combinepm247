<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User as UserTable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'user_type_id',
        'show_password',
        'username',
        'is_login',
        'gmail_login',
        'gmail_refresh_token'
    ];

    public function type(){
        return $this->belongsTo(UserType::class,"user_type_id");
    }


    public function permissions(){
        return $this->hasMany(PermissionsModel::class,"user_id");
    }


    public function hasReadWritePermission($module_id)
    {
        if($this->user_type_id === UserType::ADMIN)
        {
            return true;
        }
        return UserPermissionModel::hasReadWritePermission($module_id,$this);
    }


    public function engineer_jobs(){
        return $this->hasMany(Job::class,"engineer_id");
    }

    public function user_jobs(){
        return $this->hasMany(Job::class,"created_by");
    }

    public function anyGmailLogin(){
        $anyUserLoggedIn = UserTable::where('gmail_login', 1)->exists();
        if ($anyUserLoggedIn) {
            return 1;
        } else {
           return 0;
        }
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    const ADMIN = 1;
    const ENGINNER = 3;
    CONST OFFICEUSER = 2;
}
