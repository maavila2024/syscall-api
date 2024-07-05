<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Priority extends Model
{
    use HasFactory, HasRoles;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'boolean',
        'is_default' => 'boolean',
        'justify' => 'boolean',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

}
