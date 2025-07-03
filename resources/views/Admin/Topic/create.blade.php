@extends('Admin.layout')

@section('title', 'Danh sách Topic')

@section('css')
    <link href="{{ env('APP_URL') }}assets/admin/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('body')
<div class="container">
    <h3>Thêm mới Topic</h3>

    <form action="{{ url('admin/topic/create') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="ten_topic">Tên Topic</label>
            <input type="text" name="ten_topic" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="ten_khong_dau">Tên không dấu</label>
            <input type="text" name="ten_khong_dau" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Lưu</button>
    </form>
</div>
@endsection
@section('js')
<script src="{{ env('APP_URL') }}assets/admin/libs/jquery-toast/jquery.toast.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        @if(Session::get('msg') && Session::get('msg'))
            $.toast({
                heading:"Thông báo",
                text:"{{ Session::get('msg') }}",
                loaderBg:"#3b98b5",icon:"info", hideAfter:3000,stack:1,position:"top-right"
            });
        @endif
    });
</script>
@endsection
