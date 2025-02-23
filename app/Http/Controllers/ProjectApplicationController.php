<?php

namespace App\Http\Controllers;

use App\Models\Project;
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

        ProjectApplication::create([
            'project_id' => $project->id,
            'freelancer_id' => Auth::id(),
            'cover_letter' => $validated['cover_letter'],
            'status' => 'pending',
        ]);

        return redirect()->route('projects.show', $project->id)
                        ->with('success', 'Your application has been submitted!');
    }

    
    public function index(Project $project)
    {
        if ($project->creator_id !== Auth::id()) {
            abort(403, 'You are not the owner of this project.');
        }

        // Подгружаем заявки
        $applications = $project->applications()
            ->with('freelancer')
            ->get();

        return inertia('ProjectApplications/Index', [
            'project' => $project,
            'applications' => $applications,
        ]);
    }
}

