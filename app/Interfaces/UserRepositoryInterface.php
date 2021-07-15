<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function findByUserCode($code): Model;

    public function findByEmail($email): Model;

    public function delete(User $user);
}
