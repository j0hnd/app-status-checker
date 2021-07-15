<div class="card-body">
    <div class="form-group">
        <label for="webhookName">Name</label>
        <input type="text" class="form-control" name="name" id="webhookName" placeholder="Webhook Name" value="{{ isset($webhook) ? $webhook->name : old('name') }}">
    </div>
    <div class="form-group">
        <label for="webhookUrl">URL</label>
        <input type="text" class="form-control" name="url" id="webhookUrl" placeholder="Webhook URL" value="{{ isset($webhook) ? $webhook->url : old('url') }}">
    </div>
    <div class="form-check p-3">
        <input class="form-check-input" type="checkbox" value="on" id="isActive" name="is_active" {{ isset($webhook) ? $webhook->is_active === 1 ? "checked" : "" : "" }}>
        <label class="form-check-label" for="isActive">
            Active Webhook
        </label>
    </div>
    <div class="form-group">
        <label for="webhookUrl">Applications</label>
        <select class="select2" name="application_ids[]" id="applications" multiple="multiple" data-placeholder="Select an Application" style="width: 100%">
            <option>Select Application</option>
            @if($applications)
                @foreach($applications as $application)
                <option value="{{$application->application_code}}" {{ in_array($application->application_code, $selected_applications) === true ? "selected" : "" }}>{{$application->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
