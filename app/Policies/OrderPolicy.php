<?php

namespace App\Policies;

use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can view the order.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('client');
    }

    /**
     * Determine whether the user can answer on orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function answer(User $user, Order $order)
    {
        // если текущий пользователь - клиент и заявка не закрыта, то он может ответить
        if ($user->hasRole('client') && ($order->status == config('helpdesk.status.open'))) {
            return true;
        }
        // если текущий пользователь - менеджер и заявка назначена на него, то он может ответить
        if ($user->hasRole('manager') && ($order->assignee_id == $user->id)) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        return $user->hasAnyRoles(['manager', 'client']);
    }

    /**
     * Determine whether the user can close the order.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function close(User $user, Order $order)
    {
        return $user->hasRole('client');
    }


}
