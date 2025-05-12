<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProjectController;
use App\Models\User;
use App\Models\Project;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;
use App\Models\Review;

class UserController extends Controller
{     
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function services(User $user)
    {
        $services = $user->services()->paginate(10);
        return view('user.services', compact('user', 'services'));
    }

    public function profile(User $user)
    {
        return view('user.profile', compact('user'));
    }
}
