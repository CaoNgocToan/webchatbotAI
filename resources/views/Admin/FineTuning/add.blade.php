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
                    <div class="form-group row mb-3">
                        <label class="col-form-label col-md-2 text-right p-t-10">Chủ đề</label>
                        <div class="col-md-2">
                            <select name="system_content" id="system_content" class="form-control form-select-sm" required>
                                <option value="">-- Chọn --</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->_id }}">{{ $topic->ten_topic }}</option>
                                @endforeach

                            </select>
                        </div>
                    
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 text-right p-t-10">Câu hỏi</label>
                        <div class="col-md-10">
                            <textarea name="user_content" id="user_content" rows="10" class="form-control" placeholder="Nhập nội dung câu hỏi" required>{{ old('user_content') }} </textarea>                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 text-right p-t-10">Câu trả lời</label>
                        <div class="col-md-10">
                            <textarea name="assistant_content" id="assistant_content" rows="10" class="form-control" placeholder="Nhập nội dung trả lời" required>{{ old('assistant_content') }} </textarea>                            
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