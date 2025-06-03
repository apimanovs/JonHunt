<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:5',
            'project_id' => 'nullable|exists:projects,id',
            'job_advertisement_id' => 'nullable|exists:job_advertisements,id',
        ]);
    
        $swears = \Illuminate\Support\Facades\File::exists(resource_path('swears.txt'))
            ? array_map('trim', file(resource_path('swears.txt')))
            : [];
    
        $reasonText = $request->input('reason');
    
        foreach ($swears as $swear) {
            if (stripos($reasonText, $swear) !== false) {
                return back()->withErrors(['reason' => 'Your report contains inappropriate language.']);
            }
        }

        $existingReport = Report::where('user_id', auth()->id())
            ->where(function ($query) use ($request) {
                if ($request->project_id) {
                    $query->where('project_id', $request->project_id);
                }
                if ($request->job_advertisement_id) {
                    $query->where('job_advertisement_id', $request->job_advertisement_id);
                }
            })->first();

        if ($existingReport != null)
        {
            return back()->withErrors(['reason' => 'You have already submitted report for this unit.']);
        }
    
        Report::create([
            'user_id' => auth()->id(),
            'reason' => $reasonText,
            'project_id' => $request->project_id,
            'job_advertisement_id' => $request->job_advertisement_id,
        ]);
    
        return back()->with('success', 'Report submitted successfully');
    }    
}
