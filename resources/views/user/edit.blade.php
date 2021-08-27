@extends('layouts.app')

@section('content')
    <form id="userForm" autocomplete="off">
        @include('user.partials.edit_form', ['user' => $user])
    </form>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            $.validator.setDefaults({
                submitHandler: function () {
                    $.ajax({
                        url: "{{ url('/users/update') }}/{{ $user->user_code }}",
                        type: "put",
                        data: $("#userForm").serialize(),
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                toastr.success("A user has been updated");
                                $("#userForm").html(response.data.html);
                            } else {
                                toastr.error("Oops! Something went wrong.")
                            }
                        },
                        error: function (err) {
                            var error_message = "";
                            $.each(err.responseJSON.errors, function (k, v) {
                                if (k == 'email') {
                                    error_message += "The email address is already taken";
                                } else {
                                    error_message += v[0];
                                }
                            });

                            Swal.fire({
                                icon: 'error',
                                title: error_message
                            });
                        }
                    });
                }
            });

            $('#userForm').validate({
                rules: {
                    firstname: {
                        required: true
                    },
                    lastname: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    firstname: {
                        required: "Please enter your firstname"
                    },
                    lastname: {
                        required: "Please enter your lastname"
                    },
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address",
                    }
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
