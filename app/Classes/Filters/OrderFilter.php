<?php


namespace App\Classes\Filters;


use App\Order;
use App\User;

class OrderFilter extends QueryFilter
{
    public function viewed($value)
    {
        $authUserId = $this->request->user()->id;

        if ($value == 'viewed') {
            $this->builder->whereHas('users', function ($query) use ($authUserId) {

                $query->where('user_id', $authUserId);
            });
        }
        elseif ($value == 'no-viewed') {

            $this->builder
                ->leftJoin('order_user', 'orders.id', '=','order_user.order_id')
                ->where('order_user.order_id' , '=', null)
                ->orWhere('order_user.user_id' , '!=', $authUserId)
                ->select('orders.*');
        }
    }

    public function status($value)
    {
        $this->builder->where('status', $value);
    }

    public function hasManagerAnswer($value)
    {
        // массив parent_id ответов
        $arrayAnswerParentId = Order::where('parent_id', '>', '0')->get()->pluck('parent_id');

        //массив содержащий id пользователей с ролью manager
        $arrayManagerId = User::whereHas('roles', function ($q){
            $q->where('name', config('helpdesk.roles.manager'));
        })->get()->pluck('id');


        if ($value == 'answered') {
            $this->builder
                ->whereIn('id', $arrayAnswerParentId)
                ->whereIn('author_id', $arrayManagerId);
        }
        elseif ($value == 'no-answered'){
            $this->builder
                ->whereNotIn('id', $arrayAnswerParentId)
                ->orWhere(function ($q) use($arrayAnswerParentId, $arrayManagerId){
                    $q->whereIn('id', $arrayAnswerParentId)
                        ->whereNotIn('author_id', $arrayManagerId);
                });
        }

    }
}
