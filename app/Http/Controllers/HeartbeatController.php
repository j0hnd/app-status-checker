<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonRequest;
use App\Repositories\ApplicationRepository;
use App\Repositories\HealthLogRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use DataTables;


class HeartbeatController extends Controller
{
    protected $healthLogRepository;

    protected $applicationRepository;


    public function __construct(
        HealthLogRepository $healthLogRepository,
        ApplicationRepository $applicationRepository
    )
    {
        $this->healthLogRepository = $healthLogRepository;

        $this->applicationRepository = $applicationRepository;
    }

    public function index(): View
    {
        $applications = $this->applicationRepository->findApplicationsForMonitoring();

        return view('heartbeat.index', [
            'page_title' => 'Logs',
            'breadcrumb_parent' => 'Logs',
            'applications' => $applications
        ]);
    }

    public function logs(CommonRequest $request, $code): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;

        if ($request->ajax()) {
            $application = $this->applicationRepository->findByApplicationCode($code);

            if ($application) {
                $logs = $this->healthLogRepository->getApplicationLogs($application->id);

                if ($logs) {
                    return Datatables::of($logs)
                        ->addIndexColumn()
                        ->addColumn('created_at', function ($row) {
                            return date('m d, Y H:i:s', strtotime($row->created_at));
                        })
                        ->addColumn('extras', function ($row) {
                            return "<div style='white-space: normal; width: 1000px'>".unserialize($row->extras)."</div>";
                        })
                        ->escapeColumns([])
                        ->make(true);
                }
            }
        }

        return response()->json($response, $http_code);
    }

    public function recent_logs(CommonRequest $request, $code): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;

        if ($request->ajax()) {
            $application = $this->applicationRepository->findByApplicationCode($code);

            if ($application) {
                $logs = $this->healthLogRepository->getRecentApplicationLogs($application->id);

                if ($logs) {
                    $content = view('heartbeat.partials.logs', ['logs' => $logs])->render();
                } else {
                    $content = "";
                }

                $response = [
                    'success' => true,
                    'data' => [
                        'content' => $content
                    ]
                ];

                $http_code = 200;
            }
        }

        return response()->json($response, $http_code);
    }
}
