@extends('layouts.app')

@section('content')
    <form id="changePasswordForm" autocomplete="off">
        <div class="row">
            <div class="col-md-7">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Change Password</h3>
                    </div>

                    @include('user.partials.change_password_form')
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-link toggle-cancel-button" data-url="{{ route('dashboard.index') }}">Cancel</button>
                <button type="submit" class="btn btn-primary" id="toggle-submit-change-password">Save</button>
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
                        url: "{{ route('user.save_change_password') }}",
                        type: "put",
                        data: $("#changePasswordForm").serialize(),
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                toastr.success("Your password has been updated");
                                $("#changePasswordForm")[0].reset();
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

            $('#changePasswordForm').validate({
                rules: {
                    old_password: {
                        required: true,
                    },
                    new_password: {
                        required: true,
                        minlength: 8
                    },
                    re_type_password: {
                        required: true,
                        equalTo: "#new_password"
                    },
                },
                messages: {
                    old_password: {
                        required: "Old password field is required",
                    },
                    new_password: {
                        required: "New password field is required",
                        minlength: "Must be at least eight (8) characters long"
                    },
                    re_type_password: {
                        required: "Re-type password field is required",
                        equalTo: "Re-type password field should be the same as the new password field"
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
