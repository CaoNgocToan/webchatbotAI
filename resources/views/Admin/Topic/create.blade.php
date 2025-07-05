@extends('Admin.layout')

@section('title', 'Danh sách Topic')

@section('css')
    <link href="{{ env('APP_URL') }}assets/admin/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('body')
<div class="card-box">
    <div class="row">
        <div class="col-12 col-md-12">
            <h3 class="m-t-0"> New Topic</h3>
                    

            <form action="{{ url('admin/topic/create') }}" method="POST">
                @csrf
                <div class="form-body">
                    <hr />
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="ten_topic">Tên chủ đề</label>
                    <input type="text" name="ten_topic" class="form-control" placeholder="Nhập tên của chủ đề" required>
                </div>

                <div class="form-group">
                    <label for="ten_khong_dau">Tên không dấu</label>
                    <input type="text" name="ten_khong_dau" class="form-control" placeholder="Bỏ trống nếu bạn muốn tự động bỏ dấu">
                </div>
                <div class="form-actions">
                    <a href="{{ env('APP_URL') }}admin/fine-tuning" class="btn btn-light"><i class="fa fa-reply-all"></i> Trở về</a>
                    <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
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
