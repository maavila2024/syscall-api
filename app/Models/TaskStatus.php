<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PhpParser\Node\Expr\Cast\Bool_;

class TaskStatus extends Model
{
    use HasFactory, HasRoles;

    protected $guarded = ['id'];
    protected $table = 'tasks_status';

    protected $casts = [
        'status' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

}
