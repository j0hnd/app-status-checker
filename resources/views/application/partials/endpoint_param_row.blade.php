@if(! is_null($endpoint_params))
    @foreach($endpoint_params as $param)
    <div class="form-row mt-3 mb-3">
        <div class="col">
            <input type="text" class="form-control" name="param[key][]" placeholder="Key" value="{{ $param->key }}">
        </div>
        <div class="col">
            <input type="text" class="form-control" name="param[value][]" placeholder="Value" value="{{ $param->value }}">
        </div>
        <div class="col-1">
            <button class="btn btn-danger toggle-remove-endpoint-parameter" title="Add Parameter"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
    </div>
    @endforeach
@else
<div class="form-row mt-3 mb-3">
    <div class="col">
        <input type="text" class="form-control" name="param[key][]" placeholder="Key" value="">
    </div>
    <div class="col">
        <input type="text" class="form-control" name="param[value][]" placeholder="Value" value="">
    </div>
    <div class="col-1">
        <button class="btn btn-danger toggle-remove-endpoint-parameter" title="Add Parameter"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
</div>
@endif
