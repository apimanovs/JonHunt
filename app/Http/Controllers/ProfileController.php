<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Skill;
use App\Models\Project;
use App\Models\Avatar;
use App\Models\JobAdvertisement;
use App\Models\Freelancer;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user(); 
        
        $user->fill($request->validated());
        
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }
    
        $user->save();
    
        return Redirect::route('profile.edit');
    }
    

    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
    
        $avatar = Avatar::where('user_id', $user->id)->first();
    
        $projects = Project::where('creator_id', $user->id)
            ->where('Status', 'approved')
            ->get();
    
        $jobAds = JobAdvertisement::where('creator_id', $user->id)
            ->where('Status', 'approved')
            ->get()
            ->map(function ($ad) {
                return [
                    'id' => $ad->id,
                    'title' => $ad->Title,
                    'description' => $ad->Description,
                    'price' => $ad->Price,
                    'creator' => $ad->creator,
                    'created_at' => $ad->created_at,
                ];
            });
    
        $skills = [];
        $freelancer = null;
        if ($user->role === 'freelancer') {
            $freelancer = Freelancer::with('skills')->where('user_id', $user->id)->first();
            if ($freelancer) {
                $skills = $freelancer->skills->pluck('name');
            }
        }
    
        return Inertia::render('UserProfile', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $avatar,
                'role' => $user->role,
                'description' => $user->description,
            ],
            'freelancer' => $freelancer,
            'skills' => $skills,
            'projects' => $projects,
            'jobAds' => $jobAds,
        ]);
    }    
    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
}
