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
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 text-right p-t-10">Câu hỏi</label>
                        <div class="col-md-4">
                            <textarea name="user_content" id="user_content" rows="10" class="form-control" placeholder="Nhập nội dung câu hỏi" required>{{ old('user_content') }} </textarea>                            
                        </div>
                        <button type="button" class="btn btn-success pb-4 py-2 px-1" style="height: 40px; font-size: 12px; line-height: 1.2;">
                            Gợi ý thêm câu hỏi <br><i class="fa fa-arrow-right"></i>
                        </button>

                        <div class="col-md-4">
                            <textarea name="user_content_suggest" id="user_content_suggest" rows="10" class="form-control" placeholder="Câu hỏi gợi ý thêm" required>{{ old('user_content_suggest') }} </textarea>                            
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