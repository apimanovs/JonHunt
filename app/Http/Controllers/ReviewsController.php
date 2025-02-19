<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Review;
use App\Models\JobAdvertisement;
use Illuminate\Support\Facades\Auth;
use App\Services\ProfanityFilterService;

class ReviewsController extends Controller
{
    public function addReview(Request $request, Project $project,  ProfanityFilterService $profanityFilter)
{
    $validated = $request->validate([
        'Rating' => 'required|integer|between:1,5',
        'ReviewText' => 'nullable|string|max:1000',
    ]);

    if (Auth::id() == $project->creator_id) {
        return redirect()->route('projects.show', $project->id)
            ->withErrors(['error' => 'You cannot review your own project.']);
    }
    
    if ($profanityFilter->containsBadWords($validated['ReviewText'])) {
        return redirect()->route('projects.show', $project->id)
            ->withErrors(['error' => 'Your review contains inappropriate language.']);
    }
    
    $reviewedUserId = $project->creator_id;

    $project->reviews()->create([
        'UserID' => Auth::id(),
        'ReviewedUserID' => $reviewedUserId,  
        'Rating' => $validated['Rating'],
        'ReviewText' => $validated['ReviewText'],
    ]);

    return redirect()->route('projects.show', $project->id)
                     ->with('success', 'Review added successfully.');
}

public function addJobAdReview(Request $request, JobAdvertisement $jobAd, ProfanityFilterService $profanityFilter)
{
    $validated = $request->validate([
        'Rating' => 'required|integer|between:1,5',
        'ReviewText' => 'nullable|string|max:1000',
    ]);

    if (Auth::id() == $jobAd->creator_id) {
        return redirect()->route('jobAds.display', $jobAd->id)
            ->withErrors(['error' => 'You cannot review your own job ad.']);
    }
    
    if ($profanityFilter->containsBadWords($validated['ReviewText'])) {
        return redirect()->route('jobAds.display', $jobAd->id)
            ->withErrors(['error' => 'Your review contains inappropriate language.']);
    }
    
    $reviewedUserId = $jobAd->creator_id;

    $jobAd->reviews()->create([
        'UserID' => Auth::id(),
        'ReviewedUserID' => $reviewedUserId,
        'Rating' => $validated['Rating'],
        'ReviewText' => $validated['ReviewText'],
    ]);

    return redirect()->route('jobAds.display', $jobAd->id)
                     ->with('success', 'Review added successfully.');
}


public function deleteReview(Request $request, Review $review)
{
    $this->authorize('delete', $review);

    $reviewableId   = $review->reviewable_id;
    $reviewableType = $review->reviewable_type;

    $review->delete();

    if ($reviewableType === 'App\\Models\\Project') {
        return redirect()->route('projects.show', $reviewableId)
            ->with('success', 'Review deleted successfully.');
    } elseif ($reviewableType === 'App\\Models\\JobAdvertisement') {
        return redirect()->route('jobAds.display', $reviewableId)
            ->with('success', 'Review deleted successfully.');
    }

    // Если тип неизвестен — редирект назад
    return redirect()->back()->with('success', 'Review deleted successfully.');
}

    public function editReview(Request $request, Review $review)
    {
        $this->authorize('update', $review);
    
        $validated = $request->validate([
            'Rating' => 'required|integer|between:1,5',
            'ReviewText' => 'nullable|string|max:1000',
        ]);
    
        $review->update($validated);
    
        if ($review->reviewable_type === 'App\\Models\\Project') {
            return redirect()->route('projects.show', $review->reviewable_id)
                ->with('success', 'Review updated successfully.');
        } elseif ($review->reviewable_type === 'App\\Models\\JobAdvertisement') {
            return redirect()->route('jobAds.display', $review->reviewable_id)
                ->with('success', 'Review updated successfully.');
        }
    
        return redirect()->back()->with('success', 'Review updated successfully.');
    }
}
