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
                    @include('dashboard.partials._row', [
                        'callout' => $callout,
                        'font_color' => $font_color,
                        'icon' => $icon,
                        'application_name' => $application->name,
                        'application_code' => $application->code,
                        'created_at' => $logs->created_at->format('M d, Y H:i:s'),
                        'http_code' => $logs->http_code,
                        'is_public' => $is_public
                    ])
                </div>
            @endforeach
        @endif

    @endforeach
@endif
