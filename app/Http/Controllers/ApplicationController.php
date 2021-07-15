<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Repositories\ApplicationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use DataTables;


class ApplicationController extends Controller
{
    protected $applicationRepository;


    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function index(): View
    {
        return view('application.index', [
            'page_title' => 'Applications',
            'breadcrumb_parent' => 'Applications',
        ]);
    }

    public function create(): View
    {
        return view('application.create', [
            'page_title' => 'Add Application',
            'breadcrumb_parent' => 'Application',
            'breadcrumb_child' => 'Add'
        ]);
    }

    public function store(ApplicationRequest $request): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;
        $is_monitored = Application::APPLICATION_IS_NOT_MONITORED;
        $frequency = "";

        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $input = $request->only(['name', 'description', 'application_url', 'application_type', 'is_monitored', 'frequency']);

                if ($request->get('is_monitored')) {
                    $is_monitored = Application::APPLICATION_IS_MONITORED;
                    $frequency = $input['frequency'];
                }

                $endpoint_info = [
                    'details' => [
                        'method' => $request->get('method'),
                        'field_type' => $request->get('field_type'),
                        'token_url' => $request->get('token_url'),
                        'authorization_type' => $request->get('authorization_type'),
                        'app_key' => $request->get('app_key'),
                        'app_secret' => $request->get('app_secret'),
                        'username' => $request->get('username'),
                        'password' => $request->get('password')
                    ],
                    'params' => $request->get('param')
                ];

                Application::set_endpoint_info($endpoint_info);

                $application = new Application;
                $application->name = $input['name'];
                $application->description = $input['description'];
                $application->application_url = $input['application_url'];
                $application->application_type = $input['application_type'];
                $application->is_monitored = $is_monitored;
                $application->frequency = $frequency;
                $application->added_by = Auth::user()->id;

                if ($application->save()) {
                    $response['success'] = true;
                    $http_code = 200;
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

        $application = $this->applicationRepository->findByApplicationCode($code);

        if (! $application->exists) {
            abort(404);
        }

        return view('application.edit', [
            'page_title' => 'Update Application',
            'breadcrumb_parent' => 'Application',
            'breadcrumb_child' => 'Update',
            'application' => $application
        ]);
    }

    public function update(ApplicationRequest  $request, $code): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;
        $is_monitored = Application::APPLICATION_IS_NOT_MONITORED;
        $frequency = "";

        $application = $this->applicationRepository->findByApplicationCode($code);

        if ($application->exists) {
            if ($request->get('is_monitored')) {
                $is_monitored = Application::APPLICATION_IS_MONITORED;
                $frequency = $request->get('frequency');
            }

            $application->name = $request->get('name');
            $application->application_url = $request->get('application_url');
            $application->application_type = $request->get('application_type');
            $application->description = $request->get('description');
            $application->is_monitored = $is_monitored;
            $application->frequency = $frequency;

            $endpoint_info = [
                'details' => [
                    'method' => $request->get('method'),
                    'field_type' => $request->get('field_type'),
                    'token_url' => $request->get('token_url'),
                    'authorization_type' => $request->get('authorization_type'),
                    'app_key' => $request->get('app_key'),
                    'app_secret' => $request->get('app_secret'),
                    'username' => $request->get('username'),
                    'password' => $request->get('password')
                ],
                'params' => $request->get('param')
            ];

            Application::set_endpoint_info($endpoint_info);

            if ($application->save()) {
                $response['success'] = true;
                $http_code = 200;
            }
        }

        return response()->json($response, $http_code);
    }

    public function delete(Request $request): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;
        $code = $request->get('code');

        $application = $this->applicationRepository->findByApplicationCode($code);

        if ($this->applicationRepository->delete($application)) {
            $response['success'] = true;
            $http_code = 200;
        }

        return response()->json($response, $http_code);
    }

    public function get_data(Request $request): JsonResponse
    {
        $data = $this->applicationRepository->all();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('application_type', function ($row) {
                return $row->application_type == "api" ? strtoupper($row->application_type) : ucwords($row->application_type);
            })
            ->addColumn('monitored', function ($row) {
                return $row->is_monitored ? "<span class='text-success'><i class='fa fa-check' aria-hidden='true'></i></span>" : "<span class='text-secondary'><i class='fa fa-minus' aria-hidden='true'></i></span>";
            })
            ->addColumn('actions', function ($row) {
                $buttons = "<button class='btn btn-link toggle-edit-application' title='Edit' data-code='". $row->application_code ."'><i class='fa fa-eye' aria-hidden='true'></i></button>";
                $buttons .= "<button class='btn btn-link text-danger toggle-logs-application' title='Logs'><i class='fa fa-server' aria-hidden='true'></i></button>";
                $buttons .= "<button class='btn btn-link text-secondary toggle-remove-application' title='Remove' data-code='". $row->application_code ."'><i class='fa fa-trash' aria-hidden='true'></i></button>";

                return $buttons;
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function create_endpoint_param_row(): JsonResponse
    {
        return response()->json([
            'html' => view('application.partials.endpoint_param_row', ['endpoint_params' => null])->render()
        ], 200);
    }
}
