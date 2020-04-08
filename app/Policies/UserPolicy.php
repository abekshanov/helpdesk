<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view front end interface.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewBelow(User $user)
    {
        return $user->hasRole('manager');
    }

}
