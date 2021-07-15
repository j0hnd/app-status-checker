<div class="form-group">
    <label>Method</label>
    <select class="form-control" name="method">
        <option>Select Method</option>
        <option value="get" {{ isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->method == "get" ? "selected" : "" : "" : "" }}>GET</option>
        <option value="post" {{ isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->method == "post" ? "selected" : "" : "" : "" }}>POST</option>
        <option value="put" {{ isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->method == "put" ? "selected" : "" : "" : "" }}>PUT</option>
        <option value="delete" {{ isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->method == "delete" ? "selected" : "" : "" : "" }}>DELETE</option>
    </select>
</div>

<div class="form-group">
    <label>Token URL</label>
    <input type="text" class="form-control" name="token_url" id="token_url" placeholder="Token URL" value="{{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->token_url : "" : "" }}">
</div>

<fieldset>
    <label class="text-danger">** Please fill the fields below depending on the Token URL requirements you have provided</label>
    <div class="form-group">
        <label>Content-Type</label>
        <select class="form-control" name="content_type">
            <option>Select Content-Type</option>
            <option value="application/json" {{ isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->field_type == "application/json" ? "selected" : "" : "" : "" }}>Application/JSON</option>
        </select>
    </div>

    <div class="form-group">
        <label>Authorization</label>
        <select class="form-control" name="authorization_type" id="authorization_type">
            <option>Select Authorization</option>
            <option value="basic_auth" {{ isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->authorization_type == "basic_auth" ? "selected" : "" : "" : "" }}>Basic Auth</option>
            <option value="api_key_auth" {{ isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->authorization_type == "api_key_auth" ? "selected" : "" : "" : "" }}>API Key Auth</option>
            <option value="bearer_token" {{ isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->authorization_type == "bearer_token" ? "selected" : "" : "" : "" }}>Bearer Token</option>
        </select>
    </div>

    <div class="basic-auth-wrapper {!! isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->authorization_type == "basic_auth" ? "" : "d-none" : "d-none" : "d-none" !!}">
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="{{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->username : "" : "" }}">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="{{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->password : "" : "" }}">
        </div>
    </div>

    <div class="api-key-auth-wrapper {!! isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->authorization_type == "api_key_auth" ? "" : "d-none" : "d-none" : "d-none" !!}"">
        <div class="form-group">
            <label>APP Key</label>
            <input type="text" class="form-control" name="app_key" id="app_key" placeholder="APP Key" value="{{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->app_key : "" : "" }}">
        </div>
        <div class="form-group mb-4">
            <label>APP Secret</label>
            <input type="text" class="form-control" name="app_secret" id="app_secret" placeholder="APP Secret" value="{{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->app_secret : "" : "" }}">
        </div>
    </div>

    <div class="bearer-token-wrapper {!! isset($application) ? !is_null($application->endpoint_detail) ? $application->endpoint_detail->authorization_type == "bearer_token" ? "" : "d-none" : "d-none" : "d-none" !!}"">
        <div class="form-group mb-4">
            <label>Bearer Token</label>
            <input type="text" class="form-control" name="current_token" id="current_token" placeholder="Bearer Token" value="{{ isset($application) ? ! is_null($application->endpoint_detail) ? $application->endpoint_detail->current_token : "" : "" }}">
        </div>
    </div>
</fieldset>
