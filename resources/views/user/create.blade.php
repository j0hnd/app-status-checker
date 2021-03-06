@extends('layouts.app')

@section('content')
    <form id="userForm" autocomplete="off">
        <div class="row">
            <div class="col-md-7">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">User Details</h3>
                    </div>

                    @include('user.partials.form')
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-link toggle-cancel-button" data-url="{{ route('user.index') }}">Cancel</button>
                <button type="submit" class="btn btn-primary" id="toggle-submit-user">Save</button>
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
                        url: "{{ route('user.save') }}",
                        type: "post",
                        data: $("#userForm").serialize(),
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                toastr.success("A user has been added");
                                $("#userForm")[0].reset();
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
