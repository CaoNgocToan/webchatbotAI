@extends('Admin.layout')

@section('title', 'Danh sách Topic')

@section('css')
    <link href="{{ env('APP_URL') }}assets/admin/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('body')
<div class="container">
    <h3>Cập nhật Topic</h3>

    <form action="{{ url('admin/topic/edit/'.$ds->_id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="ten_topic">Tên Topic</label>
            <input type="text" name="ten_topic" class="form-control" value="{{ $ds->ten_topic }}" required>
        </div>

        <div class="form-group">
            <label for="ten_khong_dau">Tên không dấu</label>
            <input type="text" name="ten_khong_dau" class="form-control" value="{{ $ds->ten_khong_dau }}" required>
        </div>

        <button type="submit" class="btn btn-success mt-3">Cập nhật</button>
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
