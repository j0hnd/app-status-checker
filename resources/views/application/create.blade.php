@extends('layouts.app')

@section('custom-styles')
    <style>
        .select2-selection {
            height: 38px !important;
        }
    </style>
@endsection

@section('content')
    <form id="applicationForm" autocomplete="off">
        <div class="row">
            <div class="col-md-7">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Application Details</h3>
                    </div>
                    <!-- /.card-header -->

                    @include('application.partials.form')
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="button" class="btn btn-link toggle-cancel-button" data-url="{{ route('application.index') }}">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="toggle-submit-application">Save</button>
                    </div>
                </div>
            </div>

            <div id="endpoint-wrapper" class="col-md-5 d-none">
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
                                    <select class="form-control ml-sm-2 mr-sm-2" name="field_type" style="width: 100%;">
                                        <option>Select Type</option>
                                        <option value="param" {{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->field_ == "param" ? "selected" : "" : "" : "" }}>Param</option>
                                        <option value="body" {{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->field_ == "body" ? "selected" : "" : "" : "" }}>Body</option>
                                    </select>
                                </div>

                                <button class="btn btn-info" id="toggle-add-endpoint-parameter" title="Add Parameter"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>

                            <hr>

                            <div id="endpoint-parameter-wrapper" class="d-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            $.validator.setDefaults({
                submitHandler: function () {
                    $.ajax({
                        url: "{{ route('application.save') }}",
                        type: "post",
                        data: $("#applicationForm").serialize(),
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                toastr.success("An application has been added");
                                $("#applicationForm")[0].reset();
                            } else {
                                toastr.error("Oops! Something went wrong.")
                            }
                        },
                        error: function (err) {
                            Swal.fire({
                                icon: 'error',
                                title: err.responseJSON.message
                            });
                        }
                    });
                }
            });

            $('#applicationForm').validate({
                rules: {
                    application_name: {
                        required: true,
                    },
                    application_url: {
                        required: true,
                        url: true
                    },
                    application_type: {
                        required: true
                    },
                },
                messages: {
                    application_name: {
                        required: "Please enter the application name"
                    },
                    application_url: {
                        required: "Please enter the application URL",
                        url: "Please provide a valid URL of the application",
                    },
                    application_type: "Please select the type of the application"
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
