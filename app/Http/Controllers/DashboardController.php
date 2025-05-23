<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\JobAdvertisement;
use App\Models\JobAdvertisementPortfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Inertia\Response
     */
    public function display()
    {
        $projects = Project::with('creator.avatar')->get();
        $jobAds = JobAdvertisement::with(['creator.avatar', 'portfolios'])->get();

        return Inertia::render('Dashboard', [
            'projects' => $projects,
            'jobAds' => $jobAds,
        ]);
    }

}
