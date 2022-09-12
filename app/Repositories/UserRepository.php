<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function assignRole($user, $role)
    {
        return $user->assignRole($role);
    }

    public function syncRole($user, $role)
    {
        return $user->syncRoles($role);
    }
}
