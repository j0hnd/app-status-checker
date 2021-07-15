<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', [
            'page_title' => 'Dashboard',
            'breadcrumb_parent' => 'Dashboard',
        ]);
    }
}
