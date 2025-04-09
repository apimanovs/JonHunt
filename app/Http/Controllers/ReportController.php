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

        Report::create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'project_id' => $request->project_id,
            'job_advertisement_id' => $request->job_advertisement_id,
        ]);

        return back()->with('success', 'Report submitted successfully!');
    }
}
