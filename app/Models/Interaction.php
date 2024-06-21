<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;


class Interaction extends Model
{
    use HasFactory, HasRoles;

    protected $guarded = ['id'];

    public function interactionFiles()
    {
        return $this->hasMany(InteractionFile::class, 'interaction_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
