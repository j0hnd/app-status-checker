<div class="callout {{ $callout }}">
    <div class="row">
        <div class="col-8">
            <h5><span class="{{ $font_color }} mr-2">{!! $icon !!}</span> {{ $application_name }}</h5>
            <p>Date/Time: {{ $created_at }} ( {{ config('app.timezone') }} )</p>
        </div>
        <div class="col-4 text-right {{ $font_color }}">
            <h4><small style="color: #000; font-size: 11px;">HTTP Code: </small><strong>{{ $http_code }}</strong></h4>
            <button class="btn btn-info btn-sm text-white toggle-app-refresh" title="Refresh" data-code="{{ $application_code }}"><i class="fa fa-retweet fa-1x" aria-hidden="true"></i></button>
        </div>
    </div>
</div>
