@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <div class="row">
                        <a href="javascript:void(0)" class="btn btn-lg text-white float-right" id="toggle-add-application">
                            <h3 class="card-title"><i class="fa fa-plus-square mr-3" aria-hidden="true"></i>List of Applications</h3>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <table id="application-list" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>URL</th>
                                <th>Type</th>
                                <th class="text-center">Monitored</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade applicationLogModal" tabindex="-1" role="dialog" aria-labelledby="applicationLogLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4" style="">
                    <p>Modal body text goes here.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            var table = $("#application-list").DataTable({
                lengthChange: false,
                sFilter: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('application.get') }}"
                },
                columns: [
                    { data: "name", name: "name" },
                    { data: "application_url", name: "application_url" },
                    { data: "application_type", name: "application_type" },
                    { data: "monitored", name: "monitored"},
                    { data: "actions", name: "actions"}
                ],
                columnDefs: [
                    {
                        targets: 3,
                        className: "text-center"
                    }
                ]
            });

            $(document).on("click", "#toggle-add-application", (event) => {
                event.preventDefault();
                window.location.href = "{{ route('application.add') }}";
            });

            $('#application-list tbody').on('click', '.toggle-edit-application', function () {
                var data = table.row($(this).parents('tr')).data();
                var url = "{{ url('/application/edit') }}/";

                window.location.href = url + data.application_code;
            });

            $('#application-list tbody').on('click', '.toggle-logs-application', function () {
                var data = table.row($(this).parents('tr')).data();

                $.ajax({
                    url: "{{ url('/heartbeat/logs') }}/" + data.application_code,
                    type: 'get',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('.modal-title').html("Logs - " + data.name);
                            $('.modal-body').html(response.data.content);
                        }
                    },
                    complete: function () {
                        $('.applicationLogModal').modal('toggle');
                    }
                });
            });

            $('#application-list tbody').on('click', '.toggle-remove-application', function () {
                var data = table.row( $(this).parents('tr') ).data();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('application.delete') }}",
                            type: "put",
                            data: { code: data.application_code },
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    toastr.success("Application has been removed!")
                                    table.ajax.reload();
                                } else {
                                    toastr.error("Oops! Something went wrong.")
                                }
                            },
                            error: function () {
                                toastr.error("Unable to remove the selected application");
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection

