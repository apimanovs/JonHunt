<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\JobAdvertisement;
use App\Models\User;
use App\Models\Freelancer;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query', '');

        if (strlen($query) < 2) {
            return Inertia::render('SearchPage', [
                'query' => $query,
                'users' => [],
                'projects' => [],
                'jobAds' => [],
                'freelancers' => [],
            ]);
        }

        $users = User::where('name', 'like', "%$query%")
            ->orWhere('username', 'like', "%$query%")
            ->with('avatar')
            ->take(5)
            ->get();

        $freelancers = Freelancer::whereHas('user', function ($userQuery) use ($query) {
                $userQuery->where('name', 'like', "%$query%")
                    ->orWhere('username', 'like', "%$query%");
            })
            ->orWhere('specialization', 'like', "%$query%")
            ->orWhereHas('skills', function ($skillQuery) use ($query) {
                $skillQuery->where('name', 'like', "%$query%");
            })
            ->with(['user.avatar', 'skills']) 
            ->take(5)
            ->get();

            $projects = Project::where('Status', 'approved')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('title', 'like', "%$query%")
                        ->orWhere('niche', 'like', "%$query%")
                        ->orWhereHas('creator', function ($creatorQuery) use ($query) {
                            $creatorQuery->where('name', 'like', "%$query%")
                                ->orWhere('username', 'like', "%$query%");
                        });
                })
                ->with(['creator.avatar']) 
                ->take(5)
                ->get();

            $jobAds = JobAdvertisement::where('Status', 'approved')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('title', 'like', "%$query%")
                        ->orWhereHas('creator', function ($creatorQuery) use ($query) {
                            $creatorQuery->where('name', 'like', "%$query%")
                                ->orWhere('username', 'like', "%$query%");
                        });
                })
                ->with(['creator.avatar']) 
                ->take(5)
                ->get();

        return Inertia::render('SearchPage', [
            'query' => $query,
            'users' => $users,
            'freelancers' => $freelancers,
            'projects' => $projects,
            'jobAds' => $jobAds,
        ])->with('debug', [
            'jobAdsCount' => $jobAds->count(),
            'projectsCount' => $projects->count(),
            'freelancersCount' => $freelancers->count(),
            'usersCount' => $users->count(),
        ]);
    }
}
