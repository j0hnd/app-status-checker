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

                    <div id="logs-wrapper" class="mt-5">
                        <table id="logs" class="table">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Logs</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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

                if (! el.val()) {
                    toastr.warning("Please select application to view logs");
                    return;
                }

                if ($.fn.DataTable.isDataTable('#logs')) {
                    $('#logs').dataTable().fnClearTable();
                    $('#logs').dataTable().fnDestroy();
                }

                var table = $("#logs").DataTable({
                    lengthChange: false,
                    sFilter: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "{{ url('/heartbeat/logs') }}/" + el.val()
                    },
                    columns: [
                        { data: "created_at", name: "created_at" },
                        { data: "extras", name: "extras"}
                    ],
                    columnDefs: [
                        { width: 250, targets: 0 }
                    ],
                    fixedColumns: true
                });
            });
        });
    </script>
@endsection
