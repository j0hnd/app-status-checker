<?php

namespace App\Models;

use App\Scopes\DeletedAtScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        User::creating(function (User $user) {
            $user->user_code = Uuid::uuid4();
        });
    }

    protected static function booted()
    {
        static::addGlobalScope(new DeletedAtScope());
    }
}
