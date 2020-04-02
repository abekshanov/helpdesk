@extends('layouts.app')

@section('content')
<div class="container">
    <a type="button" href="{{route('orders.create')}}" class="btn btn-outline-info">Создать</a>

    <table class="table table-hover my-3">
        <thead>
        <tr>
            <th>Номер заявки</th>
            <th>Наименование</th>
            <th>Статус</th>
            <th>Назначена</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{$order->id}}</td>
                <td>
                    <a href="{{route('orders.show', $order->id)}}">{{$order->title}}</a>
                </td>
                <td>{{$order->status}}</td>
                <td>{{$order->assignee}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
