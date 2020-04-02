@extends('layouts.app')

@section('content')
<div class="container">

    <p>Номер заявки: {{$order->id}}</p>
    <p>Наименование: {{$order->title}}</p>
    <p>Подробное описание: {{$order->description}}</p>
    <p>Статус: {{$order->status}}</p>
    <p>Назначена: {{$order->assignee}}</p>

    @include('orders.form');
</div>
@endsection
