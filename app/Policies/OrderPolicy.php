<?php

namespace App\Policies;

use App\Classes\Services\OrderService;
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
        return $user->hasRole(config('helpdesk.roles.manager'));
    }

    /**
     * Determine whether the user can filter any orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function filter(User $user)
    {
        return $user->hasRole(config('helpdesk.roles.manager'));
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole(config('helpdesk.roles.client'));
    }

    /**
     * Determine whether the user can answer orders when status is open.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function answer(User $user, Order $order)
    {
        if (!OrderService::isOpen($order->id)) {
            return false;
        }

        if((!$order->assignee_id) && ($user->hasRole(config('helpdesk.roles.manager'))))
        {
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can accept the order.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function accept(User $user)
    {
        return $user->hasRole(config('helpdesk.roles.manager'));
    }

    /**
     * Determine whether the user can close the order.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function close(User $user)
    {
        return $user->hasRole(config('helpdesk.roles.client'));
    }


}
