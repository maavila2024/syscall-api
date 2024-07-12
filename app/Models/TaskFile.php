<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;


class TaskFile extends Model
{
    use HasFactory, HasRoles;

    protected $guarded = ['id'];

    protected $table = 'task_files';

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function getFileUrlAttribute()
    {
        return Storage::url($this->path);
    }
}
