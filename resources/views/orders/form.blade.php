<div  class="orderForm">
    {{--Вывод ошибок --}}
    <ul class=" alert alert-danger validationErrors"  style="display: none">
    </ul>

    <form method="post" enctype="multipart/form-data" action="{{route('orders.store')}}">
        {{ csrf_field()}}
        <input type="text" name="title" class="form-control mt-2" id="" placeholder="Тема">
        <textarea rows="5" name="description" id="" class="form-control mt-2" type="text" placeholder="Сообщение" ></textarea>
        <div class="custom-file mt-2">
            <input type="file" class="custom-file-input" id="customFile" name="customFile" />
            <label class="custom-file-label" for="customFile">Выберите файл</label>
        </div>
        <div class="mt-2">
            <input hidden name="status" value="{{$status}}">
            <input hidden name="author_id" value="{{Auth::user()->id}}">
            <input hidden name="parent_id" value="{{$parentId}}">
            <input type="submit" name="send"  class="btn btn-outline-primary" value="Отправить" />
            <a href="{{URL::previous()}}" type="button" class="btn btn-outline-secondary">Назад</a>
        </div>
    </form>

</div>
