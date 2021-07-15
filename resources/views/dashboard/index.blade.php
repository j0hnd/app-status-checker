@extends('layouts.app')

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
    <div class="col-md-12">
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
                @include('dashboard.partials.row', ['applications' => $applications])
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        // Enable pusher logging - don't include this in production
        @if(config('app.env') == "production")
        Pusher.logToConsole = false;
        @else
        Pusher.logToConsole = true;
        @endif
    </script>
@endsection
