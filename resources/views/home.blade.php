@extends('layouts.app')

@section('content')
<div class="container">
    <a type="button" href="{{route('orders.index')}}" class="btn btn-warning">
        Заявки
    </a>
</div>
@endsection
