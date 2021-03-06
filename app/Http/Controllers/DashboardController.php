<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonRequest;
use App\Jobs\ManualPingJob;
use App\Repositories\ApplicationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $applicationRepository;


    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function index(): View
    {
        $applications = $this->applicationRepository->findApplicationsForMonitoring();

        return view('dashboard.index', [
            'page_title' => 'Dashboard',
            'breadcrumb_parent' => 'Dashboard',
            'applications' => $applications,
            'groups' => $this->applicationRepository->getGroups()
        ]);
    }

    public function public_logs(): View
    {
        $applications = $this->applicationRepository->findApplicationsForMonitoring();

        return view('dashboard.public_logs', [
                'applications' => $applications
            ]);
    }

    public function manual_refresh(CommonRequest $request, $application_code = null): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 401;

        if ($request->ajax()) {
            if (is_null($application_code)) {
                $applications = $this->applicationRepository->findApplicationsForMonitoring();
            } else {
                $applications = $this->applicationRepository->findApplicationForMonitoring($application_code);
            }

            ManualPingJob::dispatch($applications)->delay(now()->addMinutes(2));

            $response['success'] = true;
            $http_code = 200;
        }

        return response()->json($response, $http_code);
    }

    public function filter(CommonRequest $request): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 401;

        if ($request->ajax()) {
            $group = $request->get('group', null);

            if ($group) {
                $applications = $this->applicationRepository->findByGroup($group);
            } else {
                $applications = $this->applicationRepository->findApplicationsForMonitoring();
            }

            if ($applications) {
                $html = view('dashboard.partials.row', [
                    'applications' => $applications,
                    'is_public' => false
                ])->render();

                $response = [
                  'success' => true,
                  'data' => [
                      'html' => $html
                  ]
                ];

                $http_code = 200;
            }
        }

        return response()->json($response, $http_code);
    }
}
