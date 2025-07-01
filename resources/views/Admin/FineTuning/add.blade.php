@extends('Admin.layout')
@section('title', 'Thêm mới Fine Tunning')
@section('body')
<div class="card-box">
    <div class="row">
        <div class="col-12 col-md-12">
            <h3 class="m-t-0"><a href="{{ env('APP_URL') }}admin/fine-tuning" class="btn btn-primary btn-sm"><i class="fas fa-reply-all"></i> Trở về</a> New Fine-Tuning</h3>
            <form action="{{ env('APP_URL') }}admin/fine-tuning/create" method="post" id="FineTuningform">
                {{ csrf_field() }}
                <div class="form-body">
                    <hr />
                    @if($errors->any())
                        <div class="alert alert-success">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 text-right p-t-10">system</label>
                        <div class="col-md-10">
                            <textarea name="system_content" id="system_content" rows="10" class="form-control" placeholder="Nhập nội dung system" required>{{ old('system_content') }} </textarea>                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 text-right p-t-10">user</label>
                        <div class="col-md-10">
                            <textarea name="user_content" id="user_content" rows="10" class="form-control" placeholder="Nhập nội dung user" required>{{ old('user_content') }} </textarea>                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 text-right p-t-10">assistant</label>
                        <div class="col-md-10">
                            <textarea name="assistant_content" id="assistant_content" rows="10" class="form-control" placeholder="Nhập nội dung assistant" required>{{ old('assistant_content') }} </textarea>                            
                        </div>
                    </div>
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