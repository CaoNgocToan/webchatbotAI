@extends('layouts.app')

@section('content')
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
