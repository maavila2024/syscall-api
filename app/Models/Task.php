<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;


class Task extends Model
{
    use HasFactory, HasRoles;

    protected $guarded = ['id'];

    protected $casts = [
        'priority_id' => 'integer',
    ];

    // protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class,'owner_id', 'id');
    }

    public function taskStatus()
    {
        return $this->hasOne(TaskStatus::class, 'id', 'task_status_id');
    }

    public function priority()
    {
        return $this->hasOne(Priority::class, 'id', 'priority_id');
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }
}
