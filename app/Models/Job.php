<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;
    public $table = "jobs";
    protected $fillable = ["customer_email","postcode","created_by", "added_by", "date", "engineer_id","agent_id", "job_invoice_no", "hand_overed_agent", "status", "contract_status", "job_type","update_status"];

    /**
     * Scope: for non-admin users, only jobs where they are ADDED BY, Agent ASSIGNED, or HANDED OVER.
     * Admins see all jobs.
     */
    public function scopeVisibleToUser(Builder $query, User $user): Builder
    {
        if ($user->user_type_id === UserType::ADMIN) {
            return $query;
        }
        return $query->where(function (Builder $q) use ($user) {
            $q->where('created_by', $user->id)
                ->orWhere('added_by', $user->name)
                ->orWhere('agent_id', $user->id)
                ->orWhere('hand_overed_agent', $user->id);
        });
    }

    public function engineer_user(){
        return $this->belongsTo(User::class,"engineer_id");
    }
    public function agent_assigned(){
        return $this->belongsTo(User::class,"agent_id");
    }
    public function handed_over(){
        return $this->belongsTo(User::class,"hand_overed_agent");
    }
    public function created_by_user(){
        return $this->belongsTo(User::class,"created_by");
    }
    public function added_by_user_name(){
        if ($this->created_by) {
            return $this->created_by_user->name;
        }else{
            return $this->added_by;
        }
    }
    public function contract(){
        return $this->hasOne(Contract::class,"job_id");
    }
    public function payment(){
        return $this->hasOne(Payment::class,"job_id");
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($job) {
            $job->contract()->delete();
            $job->payment()->delete();
        });
    }
}
