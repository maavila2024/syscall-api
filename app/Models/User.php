<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    protected $with = ['teams', 'tasks'];


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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function resetPasswordTokens()
    {
        return $this->hasMany(PasswordResetToken::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Team::class,
            table: 'model_has_roles',
            foreignPivotKey: 'model_id',
            relatedPivotKey: 'team_id'
        );
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class, 'user_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'owner_id', 'id');
    }

    // public function tasks()
    // {
    //     return $this->hasMany(
    //         // related: Task::class,
    //         // foreignKey: 'owner_id',
    //         // localKey: 'user_id'
    //         Task::class, 'owner_id', 'id'
    //     );
    // }
}
