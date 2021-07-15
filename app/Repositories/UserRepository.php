<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UserRepository extends  BaseRepository implements UserRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function all(): Collection
    {
        return User::select('id', 'user_code', 'firstname', 'lastname', 'email')
            ->orderBy('created_at', 'desc')->get();
    }

    public function findByUserCode($code): Model
    {
        return User::where('user_code', $code)->firstOrFail();
    }

    public function findByEmail($email): Model
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}
