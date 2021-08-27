@extends('layouts.app')

@section('content')
    <div id="form-wrapper">
        @include('webhook.partials.edit_form', [ 'webhook' => $webhook ])
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            $("#applications").select2({
                theme: 'bootstrap4'
            });

            $.validator.setDefaults({
                submitHandler: function () {
                    $.ajax({
                        url: "{{ url('webhook/update') }}/{{ $webhook->webhook_code }}",
                        type: "put",
                        data: $("#webhookForm").serialize(),
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                toastr.success("The selected webhook has been updated");
                                $("#form-wrapper").html(response.data.html);
                                $("#applications").select2({
                                    theme: 'bootstrap4'
                                });
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

            $('#webhookForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    url: {
                        required: true,
                        url: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter the webhook name"
                    },
                    url: {
                        required: "Please enter the webhook URL",
                        url: "Please provide a valid URL of the webhook",
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
