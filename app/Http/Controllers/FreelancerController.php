<?php

namespace App\Http\Controllers;

use App\Models\Freelancer;
use App\Models\User;
use App\Models\Skill;
use App\Models\PortfolioPhoto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class FreelancerController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
    
        if ($user->role === 'freelancer' || $user->freelancer) {
            return response()->json([
                'message' => 'You are already registered as a freelancer.'
            ], 422);
        }
    
        $validatedData = $request->validate([
            'country' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'specialization' => [
                'required', 'string', 'max:255',
                Rule::in([
                    'Web Development',
                    'Graphic Design',
                    'Content Writing',
                    'Digital Marketing',
                    'SEO',
                    'Mobile App Development',
                    'UI/UX Design',
                ]),
            ],
            'experience_from' => 'required|integer|min:1900|max:2100',
            'experience_to' => 'nullable|integer|min:1900|max:2100',
            'skills' => 'array|max:5',
            'skills.*' => 'string|exists:skills,name',
        ]);
    
        $freelancer = Freelancer::create([
            'user_id'         => $user->id,
            'country'         => $validatedData['country'],
            'bio'             => $validatedData['bio'],
            'specialization'  => $validatedData['specialization'],
            'experience_from' => $validatedData['experience_from'],
            'experience_to'   => $validatedData['experience_to'] ?? null,
        ]);
    
        if (!empty($validatedData['skills'])) {
            $skillIds = Skill::whereIn('name', $validatedData['skills'])->pluck('id');
            $freelancer->skills()->sync($skillIds);
        }
    
    
        return redirect()->route('dashboard')->with('success', 'Your freelance profile went for moderation.');
    }
    

    public function display($username)
    {
        $user = Auth::user();
    
        if ($user->username !== $username) {
            abort(403, 'You are not authorized to edit this profile.');
        }
    
        $freelancer = Freelancer::where('user_id', $user->id)->first();
    
        return Inertia::render('EditFreelanceProfile', [
            'freelancer' => $freelancer,
            'user' => $user,
        ]);
    }

    public function update(Request $request, $username)
    {
        $user       = Auth::user();
        $freelancer = Freelancer::firstOrCreate(['user_id' => $user->id]);

        $data = $request->validate([
            'country'          => 'required|string|max:255',
            'bio'              => 'nullable|string|max:1000',
            'specialization'   => [
                'required', 'string',
                Rule::in([
                    'Web Development','Graphic Design','Content Writing',
                    'Digital Marketing','SEO','Mobile App Development','UI/UX Design',
                ]),
            ],
            'experience_from'  => 'required|integer|min:1900|max:' . date('Y'),
            'experience_to'    => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        $freelancer->country         = $data['country'] ?? null;
        $freelancer->bio            = $data['bio'] ?? null;
        $freelancer->specialization = $data['specialization'] ?? null;
        $freelancer->experience_from= $data['experience_from'] ?? null;
        $freelancer->experience_to  = $data['experience_to'] ?? null;
        $freelancer->save();
        
        return back()->with('success', 'Profile updated successfully.');
    }


    public function getFreelancerProfile($id)
    {
        $freelancer = Freelancer::with('user')->findOrFail($id);
        return response()->json(['freelancer' => $freelancer]);
    }
}
