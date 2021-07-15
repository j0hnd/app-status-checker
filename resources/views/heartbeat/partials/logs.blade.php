@if($logs)
    @foreach($logs as $i => $log)
<div class="row p-2 {{ ($i%2 == 0)? '': 'bg-secondary' }}">
    <div class="col-md-3">
        {{ date('m d, Y H:i:s', strtotime($log->created_at)) }}
    </div>
    <div class="col-md-9">
        {{ unserialize($log->extras) }}
    </div>
</div>
    @endforeach
@else
    <div class="row">
        <div class="col-md-12 text-danger">No Logs</div>
    </div>
@endif
