<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\JobAdvertisement;
use App\Models\Avatar;
use App\Mail\JobAdApprovedMail;
use App\Mail\FreelancerApprovedMail;
use App\Mail\ProjectApprovedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use App\Models\Report;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(): \Inertia\Response
    {

        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalJobAds = JobAdvertisement::count();

        return Inertia::render('Admin/AdminDashboard', [
            'totalUsers' => $totalUsers,
            'totalProjects' => $totalProjects,
            'totalJobAds' => $totalJobAds,
        ]);
    }

    public function usersIndex(): \Inertia\Response
    {
        $users = User::with('avatar')->orderBy('created_at', 'desc')->paginate(20);
    
        return Inertia::render('Admin/UsersIndex', [
            'users' => $users
        ]);
    }

    public function usersDestroy($userId)
    {
        $user = User::findOrFail($userId);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User is deleted!');
    }

    public function moderateProjects() {
        $pendingProjects = Project::with('creator.avatar')->where('status', 'pending')->paginate(10);
        return Inertia::render('Admin/ProjectsRequests', [
            'pendingProjects' => $pendingProjects,
            'csrf' => csrf_token(),
        ]);
    }
    
    public function moderateJobAds() {
        $pendingJobAds = JobAdvertisement::with('creator.avatar')->where('status', 'pending')->paginate(10);
        return Inertia::render('Admin/JobAdsRequests', [
            'pendingJobAds' => $pendingJobAds,
            'csrf' => csrf_token(),
        ]);
    }
    
    public function approveProject(Project $project)
    {
        $project->status = 'approved';
        $project->save();
    
        $user = User::find($project->creator_id);
     
        Mail::to($user->email)->send(new ProjectApprovedMail($project));
        
        return redirect()->back()->with('success', 'Project approved!');
    }
    
    public function rejectProject(Project $project)
    {
        $project->Status = 'rejected';
        $project->delete();
    
        return redirect()->back()->with('success', 'Project rejected!');
    }
    
    public function approveJobAd(JobAdvertisement $jobAd)
    {
        $jobAd->status = 'approved';
        $jobAd->save();
    
        $user = User::find($jobAd->creator_id);

        Mail::to($user->email)->send(new JobAdApprovedMail($jobAd));

        return redirect()->back()->with('success', 'Job Ad approved!');
    }
    
    public function rejectJobAd(JobAdvertisement $jobAd)
    {
        $jobAd->Status = 'rejected';
        $jobAd->delete();
    
        return redirect()->back()->with('success', 'Job Ad rejected!');
    }
    
    public function projectsIndex(): \Inertia\Response
    {
        $projects = Project::with('creator.avatar')->orderBy('id', 'asc')->paginate(20);

        return Inertia::render('Admin/ProjectsIndex', [
            'projects' => $projects
        ]);
    }

    public function projectsDestroy(Project $project)
    {
        $project->delete();

        return back()->with('success', 'Project deleted!');
    }

    public function jobAdsIndex(): \Inertia\Response
    {
        $jobAds = JobAdvertisement::with('creator.avatar')->orderBy('id', 'asc')->paginate(20);

        return Inertia::render('Admin/JobAdsIndex', [
            'jobAds' => $jobAds
        ]);
    }

    public function jobAdsDestroy(JobAdvertisement $jobAd)
    {
        $jobAd->delete();

        return back()->with('success', 'Job Ad deleted!');
    }

    public function profile(): \Inertia\Response
    {
        $admin = auth()->user(); 

        return Inertia::render('Admin/Profile', [
            'admin' => $admin
        ]);
    }

    public function reportsIndex()
    {
        $reports = Report::with('user', 'project', 'jobAdvertisement')->latest()->paginate(20);
        return Inertia::render('Admin/Reports/ReportsIndex', [
            'reports' => $reports
        ]);
    }


    public function moderateFreelancers()
    {
        $pendingFreelancers = \App\Models\Freelancer::with('user')->where('is_approved', false)->orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Freelancers/FreelancersIndex', [
            'pendingFreelancers' => $pendingFreelancers
        ]);
    }

    public function approveFreelancer(Freelancer $freelancer)
    {
        $freelancer->is_approved = true;
        $freelancer->save();

        $user = $freelancer->user;
        $user->role = 'freelancer';
        $user->save();

        Mail::to($freelancer->user->email)->send(new FreelancerApprovedMail($freelancer));

        return redirect()->back()->with('success', 'Freelancer approved successfully!');
    }

    public function rejectFreelancer(Freelancer $freelancer)
    {
        $freelancer->delete();

        return redirect()->back()->with('success', 'Freelancer rejected and profile removed!');
    }

}
