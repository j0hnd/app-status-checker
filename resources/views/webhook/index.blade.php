@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <div class="row">
                        <a href="javascript:void(0)" class="btn btn-lg text-white float-right" id="toggle-add-webhook">
                            <h3 class="card-title"><i class="fa fa-plus-square mr-3" aria-hidden="true"></i>List of Webhooks</h3>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <table id="webhook-list" class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Active</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            var table = $("#webhook-list").DataTable({
                lengthChange: false,
                sFilter: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('webhook.get') }}"
                },
                columns: [
                    { data: "name", name: "name" },
                    { data: "active", name: "active" },
                    { data: "actions", name: "actions" }
                ],
                columnDefs: [
                    {
                        targets: 1,
                        className: "col-md-1"
                    },
                    {
                        targets: 2,
                        className: "text-center"
                    }
                ]
            });

            $(document).on("click", "#toggle-add-webhook", (event) => {
                event.preventDefault();
                window.location.href = "{{ route('webhook.add') }}";
            });

            $('#webhook-list tbody').on('click', '.toggle-edit-webhook', function () {
                var data = table.row($(this).parents('tr')).data();
                var url = "{{ url('/webhook/edit') }}/";

                window.location.href = url + data.webhook_code;
            });

            $('#webhook-list tbody').on('click', '.toggle-remove-webhook', function () {
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
                            url: "{{ route('webhook.delete') }}",
                            type: "put",
                            data: { code: data.webhook_code },
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    toastr.success("Webhook has been removed!")
                                    table.ajax.reload();
                                } else {
                                    toastr.error("Oops! Something went wrong.")
                                }
                            },
                            error: function () {
                                toastr.error("Unable to remove the selected webhook");
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection
