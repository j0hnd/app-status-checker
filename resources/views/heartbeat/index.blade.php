@extends('layouts.app')

@section('custom-styles')
    <style>
        .select2-selection {
            height: 38px !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <div class="row">
                        <a href="javascript:void(0)" class="btn btn-lg text-white float-right" id="">
                            <h3 class="card-title">Logs</h3>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="form-inline">
                        <div class="form-group" style="width: 40%">
                            <select name="application" id="applications" data-placeholder="Select an Application" style="width: 95%;">
                                <option value="">Select an Application</option>
                                @if($applications)
                                    @foreach($applications as $application)
                                        <option value="{{$application->application_code}}">{{$application->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <button class="btn btn-primary" id="toggle-application-log-filter"><i class="fa fa-filter" aria-hidden="true"></i></button>
                    </form>

                    <div id="logs-wrapper" class="mt-5"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            $('#applications').select2();

            $(document).on('click', '#toggle-application-log-filter', function (event) {
                event.preventDefault();
                var el = $('#applications');

                $.ajax({
                    url: "{{ url('heartbeat/logs') }}/" + el.val(),
                    data: {
                        all: true
                    },
                    dataType: "json",
                    beforeSend: function () {
                        $('#logs-wrapper').html("<div class='text-center'><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i><span class='ml-2'>Please wait...</span></div>");
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#logs-wrapper').html(response.data.content);
                        } else {
                            toastr.warning("No logs found on the selected application");
                        }
                    },
                    error: function () {
                        $('#logs-wrapper').html("");
                        toastr.error("Oops! Something went wrong...");
                    }
                });
            });
        });
    </script>
@endsection
