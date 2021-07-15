<?php

namespace App\Http\Controllers;

use App\Repositories\ApplicationRepository;
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
            'applications' => $applications
        ]);
    }
}
