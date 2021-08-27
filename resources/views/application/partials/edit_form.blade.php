<div class="row">
    <div class="col-md-7">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Application Details</h3>
            </div>
            <!-- /.card-header -->

            @include('application.partials.form', ['application' => $application])

            <div class="card-footer">
                <button type="button" class="btn btn-link toggle-cancel-button" data-url="{{ route('application.index') }}">Cancel</button>
                <button type="submit" class="btn btn-primary" id="toggle-update-application">Update</button>
            </div>

        </div>
    </div>

    <div id="endpoint-wrapper" {!! $application->application_type == 'api' ? 'class="col-md-5"' : 'class="col-md-5 d-none"' !!}>
        <div id="endpoint-details-wrapper" class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Endpoint Details</h3>
            </div>

            <div class="card-body">
                @include('application.partials.endpoint_details_form')
            </div>
        </div>

        <div id="endpoint-params-wrapper" class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Endpoint Fields</h3>
            </div>

            <div class="card-body">
                <div id="key-value-wrapper">
                    <div id="type-wrapper" class="form-inline">
                        <label>Field Type</label>
                        <div class="col-5">
                            <select class="form-control ml-sm-2 mr-sm-2 width-100" name="field_type">
                                <option value="">Select Type</option>
                                <option value="param" {{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->field_type == "param" ? "selected" : "" : "" : "" }}>Param</option>
                                <option value="body" {{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->field_type == "body" ? "selected" : "" : "" : "" }}>Body</option>
                            </select>
                        </div>

                        <button class="btn btn-info" id="toggle-add-endpoint-parameter" title="Add Parameter"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </div>

                    <hr>

                    <div id="endpoint-parameter-wrapper" {!! ! is_null($application->endpoint_params) ? "" : "class='d-none'" !!}>
                        @include('application.partials.endpoint_param_row', ['endpoint_params' => $application->endpoint_params])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
