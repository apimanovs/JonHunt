<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Avatar;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request)
    {
        $user = $request->user();

        $avatar = null;

        if ($user) {
            $user->load('avatar');

            $avatar = Avatar::where('user_id', $user->id)->first();
        }

        return array_merge(parent::share($request), [
            'csrf' => csrf_token(), 
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
                'info'   => $request->session()->get('info'),
            ],
            'auth' => [
                'user' => $user ? [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'role'  => $user->role,
                    'isNewUser' => $request->session()->get('isNewUser'),
                    'balance' => $request->user()->balance->amount ?? 0, 
                    'avatar' => $avatar ? [
                        'photo_url'             => $avatar->photo_url,
                        'cloudinary_public_id'  => $avatar->cloudinary_public_id,
                    ] : null,
                ] : null,
            ],
        ]);
    }

}
