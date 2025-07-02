@extends('Admin.layout')
@section('title', 'Messages Chat')

@section('body')
<div class="card-box">
    <div class="row">
        <div class="col-12 col-md-12">
            <h3 class="m-t-0"><i class="mdi mdi-message-text"></i> Danh sách Chat</h3>
            <hr />
            @if($danhsach)
            <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Messages</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($danhsach as $ds)
                    <tr>
                        <td class="text-center">{{ date("d/m/Y H:i", strtotime($ds['created_at'])) }}</td>
                        <td>
                            <strong>{{ $ds['messages'][0]['role'] }}:</strong> {{ $ds['messages'][0]['content'] }} <br />
                            <strong>{{ $ds['messages'][1]['role'] }}:</strong> {{ $ds['messages'][1]['content'] }} <br />
                            <strong>{{ $ds['messages'][2]['role'] }}:</strong> {{ $ds['messages'][2]['content'] }} <br />
                        </td>
                        <td class="text-center" style="vertical-align:middle;">
                            <a href="{{ env('APP_URL') }}admin/messages/delete/{{$ds['_id']}}" onclick="return confirm('Are you sure?')"><i class="fa fa-trash text-danger"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $danhsach->withPath(env('APP_URL') . 'admin/messages') }}
            @endif
        </div>
    </div>
</div>

<div id="XemChiTietModal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myExtraLargeModalLabel">Messages Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="XemChiTietHTML">
                ...
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    jQuery(document).ready(function(){
        $(".xem-chi-tiet").click(function(){
            var href = $(this).attr("href");
            $.get(href, function(html){
                $("#XemChiTietHTML").html(html);
            })
        });
    });
</script>
@endsection