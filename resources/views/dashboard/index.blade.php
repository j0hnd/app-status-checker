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
                    <div class="col-10 text-left">
                        <a href="javascript:void(0)" class="btn btn-lg text-white">
                            <h3 class="card-title">Pings</h3>
                        </a>
                    </div>

                    <div class="col-2 mt-2 text-right">
                        <a href="javascript:void(0)" class="mr-2" id="toggle-refresh-logs" title="Refresh Logs"><i class="fa fa-retweet fa-1x" aria-hidden="true"></i></a>
                        <a href="{{ route('dashboard.logs') }}" target="_blank" title="Open logs on separate window"><i class="fa fa-share fa-1x" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <form class="form-inline">
                            <div class="form-group width-40">
                                <select class="form-control width-95" id="filter-group" data-placeholder="Filter by group">
                                    <option value="">Select a Group</option>
                                    @if(! is_null($groups))
                                        @foreach($groups as $group)
                                            <option value="{{ $group->group }}">{{ $group->group }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <button class="btn btn-primary" id="toggle-group-filter"><i class="fa fa-filter" aria-hidden="true"></i></button>
                        </form>
                    </div>
                </div>
                <div class="row" id="application-status-wrapper">
                    @include('dashboard.partials.row', [
                        'applications' => $applications,
                        'is_public' => false
                    ])
                </div>
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

        $(function () {
            $('.callout').matchHeight();
        });
    </script>
@endsection
