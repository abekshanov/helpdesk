<?php


namespace App\Classes\Services;


use App\Mail\OrderAnswered;
use App\Mail\OrderClosed;
use App\Mail\OrderCreated;
use App\Order;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public static function sendToManagersCreatedOrder(Order $order): Void
    {
        // отправка письма всем менеджерам при создании заявки
        $managers = UserService::getManagers();
        Mail::to($managers)->send(new OrderCreated($order));
    }

    public static function sendToManagerClosedOrder(Order $order): Void
    {
        $assigneeId = $order->assignee_id;
        if ($assigneeId){
            // если заявка назначена на менеджера
            $manager = UserService::getUserById($assigneeId);
            Mail::to($manager)->send(new OrderClosed($order));
        }
    }

    public static function sendToManagerAnswer(Order $order): Void
    {
        $parentOrder = OrderService::getById($order->parent_id);
        $assigneeId = $parentOrder->assignee_id;
        if ($assigneeId){
            // если заявка назначена на менеджера
            $manager = UserService::getUserById($assigneeId);
            Mail::to($manager)->send(new OrderAnswered($order));
        }
    }

    public static function sendEmailToClientAnswer(Order $order): Void
    {
        $parentOrder = OrderService::getById($order->parent_id);
        $client = UserService::getUserById($parentOrder->author_id);
        Mail::to($client)->send(new OrderAnswered($order));
    }

}
