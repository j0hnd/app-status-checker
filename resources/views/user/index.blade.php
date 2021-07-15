@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <div class="row">
                        <a href="{{ route('user.add') }}" class="btn btn-lg text-white float-right" id="toggle-add-user">
                            <h3 class="card-title"><i class="fa fa-plus-square mr-3" aria-hidden="true"></i>List of Users</h3>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <table id="users-list" class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Active</th>
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
            var table = $("#users-list").DataTable({
                lengthChange: false,
                sFilter: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('user.get') }}"
                },
                columns: [
                    { data: "name", name: "name" },
                    { data: "email", name: "email" },
                    { data: "active", name: "active" },
                    { data: "actions", name: "actions"}
                ],
                order: [[0, "desc"]]
            });

            $('#users-list tbody').on('click', '.toggle-edit-user', function () {
                var data = table.row($(this).parents('tr')).data();
                var url = "{{ url('/users/edit') }}/";

                window.location.href = url + data.user_code;
            });

            $('#users-list tbody').on('click', '.toggle-reset-password', function () {
                var data = table.row($(this).parents('tr')).data();
                var user_code = data.user_code;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will reset the selected user\'s password",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reset it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ url('/users/reset/password') }}/" + user_code,
                            type: "put",
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    toastr.success("A temporary password has been sent to the user.")
                                    table.ajax.reload();
                                } else {
                                    toastr.error("Oops! Something went wrong.")
                                }
                            },
                            error: function () {
                                toastr.error("Unable to reset password");
                            }
                        });
                    }
                })
            });

            $('#users-list tbody').on('click', '.toggle-remove-user', function () {
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
                            url: "{{ route('user.delete') }}",
                            type: "put",
                            data: { code: data.user_code },
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    toastr.success("User has been removed!")
                                    table.ajax.reload();
                                } else {
                                    toastr.error("Oops! Something went wrong.")
                                }
                            },
                            error: function () {
                                toastr.error("Unable to remove the selected user");
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection
