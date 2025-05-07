<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\ProjectApplicationController;
use App\Models\ProjectApplication;
use App\Models\Avatar;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;
use App\Models\Review;



class ProjectsController extends Controller
{
    public function create(): Response
    {
        $niches = ['Technology', 'Health', 'Education', 'Finance', 'Entertainment'];

        return Inertia::render('projects.create', [
            'niches' => $niches,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200|min:60',
            'description' => 'required|string|max:1500|min:100',
            'niche' => 'required|string|in:Technology,Health,Education,Finance,Entertainment,Other',
            'completion_date'  => 'nullable|date|after_or_equal:today|before_or_equal:' . Carbon::now()->addYear()->toDateString(),
            'budget_type'      => 'required|string|in:fixed,hourly',
            'budget' => 'required|numeric|min:0',
            'requirements' => 'required|string|max:1500',
            'expected_outcome' => 'required|string|max:1500',
            'tasks' => 'required|string|max:1500',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $validatedData = $validator->validated();
    
        $project = new Project();
        $project->title = $validatedData['title'];
        $project->description = $validatedData['description'];
        $project->niche = $validatedData['niche'];
        $project->completion_date = $validatedData['completion_date'] ?? null;
        $project->budget = $validatedData['budget'];
        $project->budget_type = $validatedData['budget_type'] ?? 'fixed';
        $project->requirements = $validatedData['requirements'] ?? null;
        $project->expected_outcome = $validatedData['expected_outcome'] ?? null;
        $project->tasks = $validatedData['tasks'] ?? null;
        $project->creator = Auth::user()->name;
        $project->creator_id = Auth::id();
        
        $project->Status = 'pending';
        
        $project->save();
    
        return redirect()->route('dashboard')
                         ->with('success','Project is created and sent to the moderation');
    }
    

    public function show(Project $project)
    {
        $project->load([
            'creator.avatar',
            'reviews.user',
            'applications.freelancer',
        ]);
        
        $averageRating = $project->reviews->count()
            ? round($project->reviews->avg('Rating'), 1)
            : 0;
    
        return Inertia::render('ProjectsPage', [
            'project' => $project,
            'reviews' => $project->reviews,
            'averageRating' => $averageRating,
            'applications' => $project->applications,
        ]);
    }

    public function showProjectApplications()
    {
        $user = Auth::user();
    
        $projects = Project::where('creator_id', $user->id)
            ->with('applications.freelancer')
            ->get();
    
        $projectIds = $projects->pluck('id');
        $allApplications = ProjectApplication::whereIn('project_id', $projectIds)
            ->with('freelancer', 'project')
            ->get();
    
        $myApplications = ProjectApplication::where('freelancer_id', $user->id)
            ->with('project')
            ->get();
    
        return Inertia::render('ProjectApplications/ApplicationInProfile', [
            'projects'        => $projects,
            'allApplications' => $allApplications,
            'myApplications'  => $myApplications,
        ]);
    }
}

