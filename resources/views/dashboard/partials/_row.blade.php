<div class="col-md-12">
    <div class="callout {{ $callout }}">
        <div class="row">
            <div class="col-9">
                <h5><span class="{{ $font_color }} mr-2">{!! $icon !!}</span> {{ $application_name }}</h5>
                <p>Date/Time: {{ $created_at }} ( {{ config('app.timezone') }} )</p>
            </div>
            <div class="col-3 text-right {{ $font_color }} pt-3">
                <h4><small style="color: #000; font-size: 11px;">HTTP Code: </small><strong>{{ $http_code }}</strong></h4>
            </div>
        </div>
    </div>
</div>
