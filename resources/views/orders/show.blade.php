@extends('layouts.app')

@section('content')
    <p><b>Номер заявки: </b> {{$order->id}}</p>
    <p><b>Тема:</b> {{$order->title}}</p>
    <p><b>Сообщение: </b>{{$order->description}}</p>
    <p><b>Автор: </b>{{\App\User::find($order->author_id)->name}}</p>
    <p>
        <b>Влолжение: </b>
        @if (isset($order->file_link))
            <a href="{{$order->file_link}}"  target="_blank"  type="button"
               class="badge badge-pill badge-primary ml-2">Просмотр</a>
        @else
            Отсутствует
        @endif
    </p>
    <p>
        <b>Назначена: </b>
        @if ($order->assignee_id)
            {{$order->users()->find($order->assignee_id)->name}}
        @else
            Нет исполнителя
        @endif
        @can('accept', \App\Order::class)
            <a href="{{route('orders.accept', ['id' => $order->id, 'user_id' => Auth::id()])}}" type="button"
               class="btn btn-success ml-2">Принять на выполнение</a>
        @endcan
    </p>
    <p>
        <b>Статус: </b>{{$order->status}}
       @can('close', \App\Order::class)
            <a href="{{route('orders.close', ['id' => $order->id, 'status' => config('helpdesk.status.closed')])}}" type="button"
               class="btn btn-outline-success ml-2">Закрыть заявку</a>
        @endcan
    </p>

    <div>
        <hr>
        @if ($answers->count())
            <p><b>Вложенные ответы >>></b></p>
            @foreach ($answers as $answer)
                <i>Тема: {{$answer->title}}</i><br>
                <i>Сообщение: {{$answer->description}}</i><br>
                <i>Автор: {{\App\User::find($answer->author_id)->name}}</i><br>
                <i>Влолжение: </i>
                @if (isset($answer->file_link))
                    <a href="{{$answer->file_link}}"  target="_blank"  type="button"
                       class="badge badge-pill badge-primary ml-2">Просмотр</a>
                @else
                    Отсутствует
                @endif
                <hr>
            @endforeach
        @endif
    </div>
    @can('answer', $order)
        <p><b>Ответить на заявку:</b></p>
        @include('orders.form', ['parentId' => $order->id, 'status' => config('helpdesk.status.answer')])
    @else
        <a href="{{URL::previous()}}" type="button" class="btn btn-outline-secondary">Назад</a>
    @endcan
@endsection
