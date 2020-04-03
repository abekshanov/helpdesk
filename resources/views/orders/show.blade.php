@extends('layouts.app')

@section('content')
<div class="container">

    <p><b>Номер заявки: </b> {{$order->id}}</p>
    <p><b>Наименование:</b> {{$order->title}}</p>
    <p><b>Подробное описание: </b>{{$order->description}}</p>
    <p><b>Влолжение: </b>{{$order->file_link}}</p>
    <p><b>Назначена: </b>{{$order->assignee}}</p>
    <p>
        <b>Статус: </b>{{$order->status}}
        <a href="{{route('orders.close', ['id' => $order->id, 'status' => config('helpdesk.status.closed')])}}" type="button"
           class="btn btn-outline-info ml-2">Закрыть заявку</a>
    </p>

    <hr>
    <p><b>Ответить на заявку:</b></p>
    @include('orders.form', ['parentId' => $order->id, 'status' => config('helpdesk.status.answer')])
</div>
@endsection
