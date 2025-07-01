@if($ds)
<h4>{{ $ds['email'] }} - {{ $ds['name'] }}</h4>
<p>{{ date("d/m/Y H:i", strtotime($ds['created_at'])) }}</p>
<table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
    <thead>
        <th>Messages</th>
    </thead>
    <tbody>
        @foreach($ds['messages'] as $ms)
        <tr>
            <td>{{ $ms }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif