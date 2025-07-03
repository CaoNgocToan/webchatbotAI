@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Danh sách Topic</h3>
    @if(Session::has('msg'))
        <div class="alert alert-success">{{ Session::get('msg') }}</div>
    @endif
    <a href="{{ url('admin/topic/create') }}" class="btn btn-success mb-3">Thêm mới</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên Topic</th>
                <th>Tên không dấu</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($danhsach as $item)
                <tr>
                    <td>{{ $item->ten_topic }}</td>
                    <td>{{ $item->ten_khong_dau }}</td>
                    <td>
                        <a href="{{ url('admin/topic/detail/'.$item->_id) }}" class="btn btn-info btn-sm">Chi tiết</a>
                        <a href="{{ url('admin/topic/edit/'.$item->_id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="{{ url('admin/topic/delete/'.$item->_id) }}" class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn có chắc chắn muốn xoá?')">Xoá</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $danhsach->links() }}
</div>
@endsection
