<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use App\Models\ProjectApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectApplicationController extends Controller
{
    public function store(Request $request, Project $project)
    {
        if (Auth::user()->role !== 'freelancer') {
            abort(403, 'Only freelancers can apply to projects.');
        }

        if ($project->creator_id === Auth::id()) {
            abort(403, 'You cannot apply to your own project.');
        }

        $existing = ProjectApplication::where('project_id', $project->id)
            ->where('freelancer_id', Auth::id())
            ->first();
        if ($existing) {
            return back()->withErrors(['message' => 'You have already applied to this project.']);
        }

        $validated = $request->validate([
            'cover_letter' => 'required|string|max:2000',
        ]);

        $application = ProjectApplication::create([
            'project_id' => $project->id,
            'freelancer_id' => Auth::id(),
            'cover_letter' => $validated['cover_letter'],
            'status' => 'pending',
        ]);
        
        $projectOwner = User::find($project->creator_id);
        
        Mail::to($projectOwner->email)->send(new ProjectApplicationReceivedMail($application));

        return redirect()->route('projects.show', $project->id)
                        ->with('success', 'Your application has been submitted!');
    }

    public function approve(ProjectApplication $application)
    {
        if ($application->project->creator_id !== Auth::id()) {
            abort(403, 'You are not the owner of this project.');
        }
        $application->status = 'approved';
        $application->save();

        return back()->with('success', 'Application approved!');
    }


    public function reject(ProjectApplication $application)
    {
        if ($application->project->creator_id !== Auth::id()) {
            abort(403, 'You are not the owner of this project.');
        }
        $application->status = 'reject';
        $application->save();

        return back()->with('success', 'Application rejected!');
    }

    public function indexAll()
    {
        $user = Auth::user();
    
        $projects = Project::where('creator_id', $user->id)
            ->with('applications.freelancer') 
            ->get();
    
        $projectIds = $projects->pluck('id');
        $allApplications = ProjectApplication::whereIn('project_id', $projectIds)
            ->with('freelancer', 'project')
            ->get();
    
        return Inertia::render('ProjectApplications/ApplicationInProfile', [
            'allApplications' => $allApplications,
            'projects' => $projects,
        ]);
    }
}

