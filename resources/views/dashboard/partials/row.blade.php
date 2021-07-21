@if($applications)
    @foreach($applications as $application)

        @if($application->health_logs)
            @foreach($application->health_logs->take(1) as $logs)

                @php
                    if ($logs->http_code >= 400 and $logs->http_code <= 500) {
                        $callout = "callout-warning";
                        $icon = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>";
                        $font_color = "text-warning";
                    } elseif ($logs->http_code >= 500 and $logs->http_code <= 600) {
                        $callout = "callout-danger";
                        $icon = "<i class='fa fa-times' aria-hidden='true'></i>";
                        $font_color = "text-danger";
                    } elseif ($logs->http_code >= 200 and $logs->http_code <= 300) {
                        $callout = "callout-success";
                        $icon = "<i class='fa fa-check' aria-hidden='true'></i>";
                        $font_color = "text-success";
                    } else {
                        $callout = "callout-secondary";
                        $icon = "<i class='fa fa-minus' aria-hidden='true'></i>";
                        $font_color = "text-secondary";
                    }
                @endphp

                <div class="col-md-4" id="app-{{ $application->application_code }}">
                    <div class="callout {{ $callout }}">
                        <div class="row">
                            <div class="col-8">
                                <h5><span class="{{ $font_color }} mr-2">{!! $icon !!}</span> {{ $application->name }}</h5>
                                <p>Date/Time: {{ $logs->created_at->format('M d, Y H:i:s') }} ( {{ config('app.timezone') }} )</p>
                            </div>
                            <div class="col-4 text-right {{ $font_color }}">
                                <h4><small style="color: #000; font-size: 11px;">HTTP Code: </small><strong>{{ $logs->http_code }}</strong></h4>
                                <button class="btn btn-info btn-sm text-white toggle-app-refresh" title="Refresh" data-code="{{ $application->application_code }}"><i class="fa fa-retweet fa-1x" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    @endforeach
@endif
