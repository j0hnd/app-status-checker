<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonRequest;
use App\Http\Requests\WebhookRequest;
use App\Models\Webhook;
use App\Models\WebhookApplication;
use App\Repositories\ApplicationRepository;
use App\Repositories\WebhookRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use DataTables;


class WebhookController extends Controller
{
    protected $webhookRepository;

    protected $applicationReposiory;


    public function __construct(
        WebhookRepository $webhookRepository,
        ApplicationRepository $applicationRepository
    )
    {
        $this->webhookRepository = $webhookRepository;

        $this->applicationReposiory = $applicationRepository;
    }

    public function index(): View
    {
        return view('webhook.index', [
            'page_title' => 'Webhooks',
            'breadcrumb_parent' => 'Webhooks',
        ]);
    }

    public function create(): View
    {
        $applications = $this->applicationReposiory->findApplicationsForMonitoring();

        return view('webhook.create', [
            'page_title' => 'Add Webhook',
            'breadcrumb_parent' => 'Webhook',
            'breadcrumb_child' => 'Add',
            'applications' => $applications,
            'selected_applications' => []
        ]);
    }

    public function store(WebhookRequest $request): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;
        $is_active = Webhook::WEBHOOK_NOT_ACTIVE;
        $send_all_codes = Webhook::NOT_SEND_ALL_HTTP_CODE;

        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $input = $request->only(['name', 'url', 'is_active']);

                if ($request->get('is_active')) {
                    $is_active = Webhook::WEBHOOK_ACTIVE;
                }

                if ($request->get('send_all_codes')) {
                    $send_all_codes = Webhook::SEND_ALL_HTTP_CODE;
                }

                $webhook = $this->webhookRepository->create([
                    'name' => $input['name'],
                    'url' => $input['url'],
                    'is_active' => $is_active,
                    'send_all_codes' => $send_all_codes
                ]);

                if ($webhook) {
                    $application_ids = $request->get('application_ids');

                    if ($application_ids) {
                        foreach ($application_ids as $application_id) {
                            $application = $this->applicationReposiory->findByApplicationCode($application_id);

                            if (! $application) {
                                continue;
                            }

                            $webhook->applications()->create([
                                'webhook_id' => $webhook->id,
                                'application_id' => $application->id
                            ]);
                        }

                        $response['success'] = true;
                        $http_code = 200;
                    }

                }
            }

        }

        return response()->json($response, $http_code);
    }

    public function edit($code): View
    {
        if (empty($code)) {
            abort(404);
        }

        $webhook = $this->webhookRepository->findByWebhookCode($code);

        $applications = $this->applicationReposiory->findApplicationsForMonitoring();

        $selected_applications = [];

        if (! $webhook->exists) {
            abort(404);
        }

        if ($webhook) {
            $wb_applications = $webhook->applications;

            if ($wb_applications) {
                foreach ($wb_applications as $application) {
                    $selected_applications[] = $application->applications->application_code;
                }
            }
        }

        return view('webhook.edit', [
            'page_title' => 'Update Webhook',
            'breadcrumb_parent' => 'Webhook',
            'breadcrumb_child' => 'Update',
            'webhook' => $webhook,
            'applications' => $applications,
            'selected_applications' => $selected_applications
        ]);
    }

    public function update(WebhookRequest  $request, $code): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;
        $is_active = Webhook::WEBHOOK_NOT_ACTIVE;
        $send_all_codes = Webhook::NOT_SEND_ALL_HTTP_CODE;

        $webhook = $this->webhookRepository->findByWebhookCode($code);

        if ($webhook->exists) {
            if ($request->get('is_active')) {
                $is_active = Webhook::WEBHOOK_ACTIVE;
            }

            if ($request->get('send_all_codes')) {
                $send_all_codes = Webhook::SEND_ALL_HTTP_CODE;
            }

            $webhook->name = $request->get('name');
            $webhook->url = $request->get('url');
            $webhook->is_active = $is_active;
            $webhook->send_all_codes = $send_all_codes;

            if ($webhook->save()) {
                $application_ids = $request->get('application_ids');

                $validated_application_ids = null;

                if ($application_ids) {
                    foreach ($application_ids as $application_id) {
                        $wb_application = $this->applicationReposiory->findByApplicationCode($application_id);

                        if ($wb_application) {
                            $validated_application_ids[] = $wb_application->id;
                        }
                    }

                    if ($validated_application_ids) {
                        foreach ($validated_application_ids as $validated_application_id) {
                            $save_many[] = new WebhookApplication(['application_id' => $validated_application_id]);
                        }

                        $webhook = Webhook::find($webhook->id);
                        $webhook->applications()->delete();
                        $webhook->applications()->saveMany($save_many);
                    }
                }

                $webhook = $this->webhookRepository->findByWebhookCode($code);

                $applications = $this->applicationReposiory->findApplicationsForMonitoring();

                $selected_applications = [];

                if (! $webhook->exists) {
                    abort(404);
                }

                if ($webhook) {
                    $wb_applications = $webhook->applications;

                    if ($wb_applications) {
                        foreach ($wb_applications as $application) {
                            $selected_applications[] = $application->applications->application_code;
                        }
                    }
                }

                $response['success'] = true;
                $response['data']['html'] = view('webhook.partials.edit_form', [
                    'webhook' => $webhook,
                    'applications' => $applications,
                    'selected_applications' => $selected_applications
                ])->render();

                $http_code = 200;
            }
        }

        return response()->json($response, $http_code);
    }

    public function delete(CommonRequest $request): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;
        $code = $request->get('code');

        $webhook = $this->webhookRepository->findByWebhookCode($code);

        if ($this->webhookRepository->delete($webhook)) {
            $response['success'] = true;
            $http_code = 200;
        }

        return response()->json($response, $http_code);
    }

    public function get_data(CommonRequest $request): JsonResponse
    {
        $data = $this->webhookRepository->all();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('active', function ($row) {
                if ($row->is_active) {
                    $buttons = "<button class='btn btn-link toggle-active-webhook' title='Edit' data-code='". $row->webhook_code ."'><span class='text-success'><i class='fa fa-check-square' aria-hidden='true'></i></span></button>";
                } else {
                    $buttons = "<button class='btn btn-link toggle-active-webhook' title='Edit' data-code='". $row->webhook_code ."'><span class='text-secondary'><i class='fa fa-check-square' aria-hidden='true'></i></span></button>";
                }

                return $buttons;
            })
            ->addColumn('actions', function ($row) {
                $buttons = "<button class='btn btn-link toggle-edit-webhook' title='Edit' data-code='". $row->webhook_code ."'><i class='fa fa-eye' aria-hidden='true'></i></button>";
                $buttons .= "<button class='btn btn-link text-secondary toggle-remove-webhook' title='Remove' data-code='". $row->webhook_code ."'><i class='fa fa-trash' aria-hidden='true'></i></button>";

                return $buttons;
            })
            ->escapeColumns([])
            ->make(true);
    }
}
