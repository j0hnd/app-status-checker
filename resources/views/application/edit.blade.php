@extends('layouts.app')

@section('custom-styles')
    <style>
        .select2-selection {
            height: 38px !important;
        }
    </style>
@endsection

@section('content')
    <form id="applicationForm">
        @include('application.partials.edit_form', ['application' => $application])
    </form>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            $.validator.setDefaults({
                submitHandler: function () {
                    $.ajax({
                        url: "{{ url('/application/update') }}/{{ $application->application_code }}",
                        type: "put",
                        data: $("#applicationForm").serialize(),
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                toastr.success("The selected application has been updated");
                                $("#applicationForm").html(response.data.html);
                                $("#applicationDescription").summernote();
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
