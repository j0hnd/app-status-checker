<div class="row">
    <div class="col-md-7">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">User Details</h3>
            </div>

            @include('user.partials.form', ['user' => $user])

            <div class="card-footer">
                <button type="button" class="btn btn-link toggle-cancel-button" data-url="{{ route('user.index') }}">Cancel</button>
                <button type="submit" class="btn btn-primary" id="toggle-update-user">Update</button>
            </div>
        </div>
    </div>
</div>


