<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;


class InteractionFile extends Model
{
    use HasFactory, HasRoles;

    protected $guarded = ['id'];

    protected $table = 'interaction_files';

    public function interaction()
    {
        return $this->belongsTo(Interaction::class);
    }
}
