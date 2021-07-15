<div class="card-body">
    <div class="form-group">
        <label for="applicationName">Name</label>
        <input type="text" class="form-control" name="name" id="applicationName" placeholder="Application Name" value="{{ isset($application) ? $application->name : old('name') }}">
    </div>
    <div class="form-group">
        <label for="applicationUrl">URL</label>
        <input type="text" class="form-control" name="application_url" id="applicationUrl" placeholder="Application URL" value="{{ isset($application) ? $application->application_url : old('application_url') }}">
    </div>
    <div class="form-group">
        <label for="applicationType">Type</label>
        <select class="form-control" name="application_type" id="applicationType">
            <option value="">Select Application Type</option>
            <option value="web" {{ isset($application) ? $application->application_type == 'web' ? 'selected' : '' : '' }}>Web Application</option>
            <option value="api" {{ isset($application) ? $application->application_type == 'api' ? 'selected' : '' : '' }}>API</option>
        </select>
    </div>
    <div class="form-group">
        <label for="applicationDescription">Description</label>
        <textarea class="form-control" name="description" id="applicationDescription">{{ isset($application) ? $application->description : old('description') }}</textarea>
    </div>
    <div class="form-check p-3">
        <input class="form-check-input" type="checkbox" value="on" id="monitored" name="is_monitored" {{ isset($application) ? $application->is_monitored === 1 ? "checked" : "" : "" }}>
        <label class="form-check-label" for="monitored">
            Monitor Application/API
        </label>
    </div>
    <div class="form-group">
        <label for="applicationDescription">Monitoring Frequency</label>
        <select class="form-control" name="frequency" id="frequency" {{ isset($application) ? $application->is_monitored === 1 ? "" : "disabled" : "disabled" }}>
            <option>Select Frequency</option>
            <option value="everyMinute" {{ isset($application) ? $application->frequency == 'everyMinute' ? 'selected' : '' : '' }}>Every Minute</option>
            <option value="everyTwoMinutes" {{ isset($application) ? $application->frequency == 'everyTwoMinutes' ? 'selected' : '' : '' }}>Every 2mins.</option>
            <option value="everyThreeMinutes" {{ isset($application) ? $application->frequency == 'everyThreeMinutes' ? 'selected' : '' : '' }}>Every 3mins.</option>
            <option value="everyFiveMinutes" {{ isset($application) ? $application->frequency == 'everyFiveMinutes' ? 'selected' : '' : '' }}>Every 5mins.</option>
            <option value="everyFifteenMinutes" {{ isset($application) ? $application->frequency == 'everyFifteenMinutes' ? 'selected' : '' : '' }}>Every 15mins.</option>
            <option value="everyThirtyMinutes" {{ isset($application) ? $application->frequency == 'everyThirtyMinutes' ? 'selected' : '' : '' }}>Every 30mins.</option>
            <option value="hourly" {{ isset($application) ? $application->frequency == 'hourly' ? 'selected' : '' : '' }}>Hourly</option>
            <option value="daily" {{ isset($application) ? $application->frequency == 'daily' ? 'selected' : '' : '' }}>Daily</option>
        </select>
    </div>
</div>
