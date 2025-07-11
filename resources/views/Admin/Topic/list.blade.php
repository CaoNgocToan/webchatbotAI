@extends('Admin.layout')

@section('title', 'Danh sách Topic')

@section('css')
    <link href="{{ env('APP_URL') }}assets/admin/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('body')
<div class="card-box mt-4">
    <div class="row">
        <div class="col-12 col-md-12">
            <h3 class="m-t-0">
                <a href="{{ url('admin/topic/create') }}">
                    <i class="mdi mdi-message-plus"></i>
                </a>
                
                Danh sách Topic: {{ number_format($danhsach->total() ?? count($danhsach), 0, ',', '.') }} dòng dữ liệu
            </h3>
            <hr />

            @if($danhsach && count($danhsach) > 0)
            <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">Tên Topic</th>
                        <th class="text-center">Tên không dấu</th>
                        <th class="text-center" style="width:50px;">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($danhsach as $item)
                        <tr>
                            <td class="text-center">{{ $item->ten_topic }}</td>
                            <td class="text-center">{{ $item->ten_khong_dau }}</td>
                            <td class="text-center" style="vertical-align:middle;">
                                <a href="{{ url('admin/topic/delete/' . $item->_id) }}" onclick="return confirm('Bạn có chắc chắn muốn xoá?')">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>
                                <a href="{{ url('admin/topic/edit/' . $item->_id) }}">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-end">
                {{ $danhsach->withPath(env('APP_URL') . 'admin/topic') }}
            </div>
            @else
                <p class="text-center text-muted">Chưa có dữ liệu Topic nào.</p>
            @endif
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
