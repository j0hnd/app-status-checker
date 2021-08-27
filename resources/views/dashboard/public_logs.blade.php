@extends('layouts.login')

@section('custom-styles')
    <style>
        .callout.callout-warning {
            border-left-color: #f6993f;
        }

        .callout.callout-secondary {
            border-left-color: #cccccc;
        }

        .text-warning {
            color: #f6993f !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col align-self-center">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                            <a href="javascript:void(0)" class="btn btn-lg text-white float-right">
                                <h3 class="card-title">Pings</h3>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            @include('dashboard.partials.row', [
                                'applications' => $applications,
                                'is_public' => true
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            $('.callout').matchHeight();
        });
    </script>
@endsection
