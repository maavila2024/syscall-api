<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;


class Task extends Model
{
    use HasFactory, HasRoles;

    protected $guarded = ['id'];

    protected $casts = [
        'priority_id' => 'integer',
        'status' => 'boolean',
        'segment' => 'integer',
        'task_type' => 'integer',
        'created_at' => 'date'
    ];

    // protected $with = ['user'];

    // protected $appends = ['created_at_formatted'];

    // public function getCreatedAtFormattedAttribute()
    // {
    //     return Carbon::parse($this->created_at)->format('Y-m-d');
    // }

    public function userOwner()
    {
        return $this->belongsTo(User::class,'owner_id', 'id');
    }

    public function userResponsible()
    {
        return $this->belongsTo(User::class,'responsible_id', 'id');
    }

    public function taskStatus()
    {
        return $this->hasOne(TaskStatus::class, 'id', 'task_status_id');
    }

    public function priority()
    {
        return $this->hasOne(Priority::class, 'id', 'priority_id');
    }

    public function complexity()
    {
        return $this->hasOne(Complexity::class, 'id', 'complexity_id');
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }
}
