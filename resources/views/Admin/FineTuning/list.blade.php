@extends('Admin.layout')
@section('title', 'Tập huấn Mô hình Fine Tunning')
@section('css')
    <link href="{{ env('APP_URL') }}assets/admin/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('body')
<div class="card-box">
    <div class="row">
        <div class="col-12 col-md-12">
            <h3 class="m-t-0"><a href="{{ env('APP_URL') }}admin/fine-tuning/add"><i class="mdi mdi-message-plus"></i></a> Tập huấn dữ liệu Fine-Tunning: {{ number_format($total, 0, ",", ".") }} dòng dữ liệu</h3>
            <hr />
            @if($danhsach)
    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Chủ đề</th>
                <th>Intent</th>
                <th>Câu hỏi</th>
                <th>Trả lời</th>
                <th class="text-center">#</th>
            </tr>
        </thead>
        <tbody>
            @foreach($danhsach as $ds)
                @php
                    $messages = collect($ds['messages'])->keyBy('role');
                @endphp
                <tr>
                    <td>{{ $messages['topic']['content'] ?? '-' }}</td>
                    <td>{{ $messages['intent']['content'] ?? '-' }}</td>
                    <td>
                        @if(!empty($messages['examples']['content']) && is_array($messages['examples']['content']))
                            <ul class="pl-3 mb-0">
                                @foreach($messages['examples']['content'] as $example)
                                    <li>{{ $example }}</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $messages['utter']['content'] ?? '-' }}</td>
                    <td class="text-center" style="width:60px;vertical-align:middle;">
                        <a href="{{ env('APP_URL') }}admin/fine-tuning/delete/{{ $ds['_id'] }}" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                            <i class="fa fa-trash text-danger"></i>
                        </a>
                        <a href="{{ env('APP_URL') }}admin/fine-tuning/edit/{{ $ds['_id'] }}">
                            <i class="fas fa-pen"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $danhsach->withPath(env('APP_URL') . 'admin/fine-tuning') }}
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
                loaderBg:"#3b98b5",icon:"info", hideAfter:3e3,stack:1,position:"top-right"
            });
        @endif
    });
</script>
@endsection