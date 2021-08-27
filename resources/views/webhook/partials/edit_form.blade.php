<div class="row">
    <div class="col-md-7">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Webhook Details</h3>
            </div>
            <!-- /.card-header -->

            <!-- form start -->
            <form id="webhookForm">
                @include('webhook.partials.form', ['webhook' => $webhook])

                <div class="card-footer">
                    <button type="button" class="btn btn-link toggle-cancel-button" data-url="{{ route('webhook.index') }}">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="toggle-update-webhook">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
