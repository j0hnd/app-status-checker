<?php

namespace App\Http\Controllers;

use App\Repositories\ApplicationRepository;
use App\Repositories\HealthLogRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


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

    public function logs(Request $request, $code): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;

        if ($request->ajax()) {
            $application = $this->applicationRepository->findByApplicationCode($code);

            $logs = $this->healthLogRepository->getRecentApplicationLogs($application->id);

            if ($logs) {
                $content = view('heartbeat.partials.logs', ['logs' => $logs])->render();

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
