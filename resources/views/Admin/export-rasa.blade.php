@extends('Admin.layout')
@section('title', 'Xuất File YAML từ MongoDB')
@section('body')
<div class="card-box">
    <div class="row">
        <div class="col-12 col-md-12">
            <h3 class="m-t-0">
                Xuất dữ liệu
            </h3>
            <form action="{{ env('APP_URL') }}admin/export-view/export" method="post" id="ExportForm">
                {{ csrf_field() }}
                <div class="form-body">
                    <hr />

                    {{-- Chủ đề --}}
                    <div class="form-group row mb-3">
                        <label class="col-form-label col-md-2 text-right p-t-10">Chủ đề</label>
                        <div class="col-md-3">
                            <select name="topic_slug" id="topic_slug" class="form-control form-select-sm" required>
                                <option value="tatca">-- Tất cả --</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->ten_khong_dau }}">{{ $topic->ten_topic }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Chọn loại file --}}
                    <div class="form-group row mb-3">
                        <label class="col-form-label col-md-2 text-right p-t-10">Loại file</label>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="export[]" value="nlu" id="export_nlu" checked>
                                <label class="form-check-label" for="export_nlu">nlu.yml</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="export[]" value="domain" id="export_domain" checked>
                                <label class="form-check-label" for="export_domain">domain.yml</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action_type" id="action_type" value="download">

                    <div class="form-actions">
                        <a href="{{ env('APP_URL') }}admin/fine-tuning" class="btn btn-light">
                            <i class="fa fa-reply-all"></i> Trở về
                        </a>
                        <button type="submit" class="btn btn-warning" onclick="setAction('write')">
                            <i class="fas fa-save"></i> Ghi YAML vào server
                        </button>

                    
                        <button type="submit" class="btn btn-success" onclick="setAction('download')">
                            <i class="fa fa-download"></i> Tải file
                        </button>

                    </div>
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
        @if(Session::get('msg'))
            $.toast({
                heading:"Thông báo",
                text:"{{ Session::get('msg') }}",
                loaderBg:"#3b98b5",icon:"info", hideAfter:3000,stack:1,position:"top-right"
            });
        @endif
    });
</script>
@endsection
