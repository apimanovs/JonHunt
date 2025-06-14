<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\JobAdController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobAdvertisementPortfolioController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/forget-newuser-flag', function () {
    session()->forget('isNewUser');
    return response()->json(['success' => true]);
})->name('forget.newuser');

Route::get('/user/{username}', [ProfileController::class, 'show'])->name('profile.show');

Route::get('/projects/create', function () {
    return Inertia::render('CreateProject');
})->name('/projects/create')->middleware('auth');

use App\Http\Controllers\ProjectApplicationController;

Route::post('/projects/{project}/apply', [ProjectApplicationController::class, 'store'])
    ->name('projects.apply')
    ->middleware('auth');

    Route::get('/projects/applications', [ProjectsController::class, 'showProjectApplications'])
    ->name('projects.applications.all')
    ->middleware('auth');

Route::post('/projects/applications/{application}/approve', [ProjectApplicationController::class, 'approve'])
    ->name('projects.applications.approve');

Route::post('/projects/applications/{application}/reject', [ProjectApplicationController::class, 'reject'])
    ->name('projects.applications.reject');
 

Route::get('/projects/{project}', [ProjectsController::class, 'show'])->name('projects.show');

Route::get('/gigs/{jobAds}', [JobAdController::class, 'display'])->name('jobAds.display');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jobAdvertisements', [JobAdController::class, 'index'])->name('jobAds.index');
    Route::get('/jobAdvertisements/create', [JobAdController::class, 'create'])->name('jobAds.create');
    Route::post('/jobAdvertisements', [JobAdController::class, 'store'])->name('jobAds.store');
    Route::get('/jobAdvertisements/{jobAd}/edit', [JobAdController::class, 'edit'])->name('jobAds.edit');
    Route::put('/jobAdvertisements/{jobAd}', [JobAdController::class, 'update'])->name('jobAds.update');
    Route::delete('/jobAdvertisements/delete/{jobAd}', [JobAdController::class, 'destroy'])->name('jobAds.delete');
    Route::post('/jobAdvertisements/{jobAd}/updateImage', [JobAdController::class, 'updateImage'])->name('jobAds.updateImage');
    
    Route::post('/projects', [ProjectsController::class, 'store'])->name('projects.store');
    Route::post('/projects/{project}/addReview', [App\Http\Controllers\ReviewsController::class, 'addReview'])->name('reviews.addReview');
    Route::post('/reviews/{review}/edit', [App\Http\Controllers\ReviewsController::class, 'editReview'])->name('reviews.edit');
    Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewsController::class, 'deleteReview'])->name('reviews.delete');

    Route::get('/projects-in-profile', [ProjectsController::class, 'projectsInProfile'])->name('projects.inProfile');
    Route::get('/projects/edit/{project}', [ProjectsController::class, 'editProject'])->name('projects.edit');
    Route::put('/projects/update/{project}', [ProjectsController::class, 'updateProject'])->name('projects.update');
    Route::delete('/projects/delete/{project}', [ProjectsController::class, 'delete'])->name('projects.delete');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/avatar/upload', [PhotoController::class, 'uploadPhoto'])->name('avatar.upload');
    Route::delete('/avatar/delete', [PhotoController::class, 'deleteAvatar'])->name('avatar.delete');

    Route::get('/jobs/{jobAd}/apply', [JobApplicationController::class, 'create'])->name('jobApplications.create');
    Route::post('/jobs/{jobAd}/apply', [JobApplicationController::class, 'store'])->name('jobApplications.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/freelancer-registration', function () {
        return inertia('FreelancerRegistration');
    })->name('freelancer.registration');

    Route::post('/api/become-freelancer', [FreelancerController::class, 'store'])->name('freelancer.store');
});

Route::middleware(['auth', 'verified', 'freelancer'])->group(function () {
    Route::get('/freelancer/{username}/edit', [FreelancerController::class, 'display'])->name('freelancers.display');
    Route::put('/freelancer/{username}/update', [FreelancerController::class, 'update'])->name('freelancers.update');
});


Route::post('/notifications/mark-all-as-read', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return response()->json(['success' => true]);
})->middleware('auth');

Route::post('/jobAdvertisements/{jobAd}/addReview', [App\Http\Controllers\ReviewsController::class, 'addJobAdReview'])
    ->name('jobAds.addReview');

    Route::post('/projects/{project}/addReview', [App\Http\Controllers\ReviewsController::class, 'addReview'])
    ->name('reviews.addReview');

    Route::post('/reviews/{review}/edit', [App\Http\Controllers\ReviewsController::class, 'editReview'])->name('reviews.edit');
Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewsController::class, 'deleteReview'])->name('reviews.delete');



Route::middleware('auth')->group(function () {
    Route::get('/portfolio', [JobAdvertisementPortfolioController::class, 'index'])->name('portfolio.index');
    Route::post('/portfolio', [JobAdvertisementPortfolioController::class, 'store'])->name('portfolio.store');
    Route::get('/portfolio/{portfolio}', [JobAdvertisementPortfolioController::class, 'show'])->name('portfolio.show');
    Route::delete('/portfolio/{portfolio}', [JobAdvertisementPortfolioController::class, 'destroy'])->name('portfolio.destroy');
    Route::put('/portfolio/{portfolio}', [JobAdvertisementPortfolioController::class, 'update'])->name('portfolio.update');
});


Route::middleware(['share.notifications'])
     ->get('/dashboard', [DashboardController::class, 'display'])
     ->name('dashboard');

use App\Http\Controllers\BalanceController;

Route::middleware(['auth'])->group(function () {
    Route::get('/balance', [BalanceController::class, 'index'])->name('balance.index');
    Route::post('/balance', [BalanceController::class, 'store'])->name('balance.store');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/contact-admin', [AdminMessageController::class, 'store'])->name('contact.admin');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/messages', [AdminMessageController::class, 'indexForAdmin'])->name('admin.messages');
    Route::post('/messages/{adminMessage}/reply', [AdminMessageController::class, 'reply'])->name('admin.messages.reply');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/admin-message', [AdminMessageController::class, 'store'])->name('admin-message.store');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/messages', [AdminMessageController::class, 'indexForAdmin'])->name('admin-message.index');
    Route::post('/admin/messages/{adminMessage}/reply', [AdminMessageController::class, 'reply'])->name('admin-message.reply');
    Route::get('/admin/reports', [AdminController::class, 'reportsIndex'])->name('admin.reports.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/contact-admin', function () {
        return Inertia::render('ContactAdmin');
    })->name('contact-admin.form');

    Route::post('/admin-message', [\App\Http\Controllers\AdminMessageController::class, 'store'])->name('admin-message.store');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
});


use App\Http\Controllers\OrderController;

Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/submit-work', [OrderController::class, 'submitWork'])->name('orders.submit-work');
    Route::post('/orders/{order}/complete', [OrderController::class, 'completeOrder'])->name('orders.complete');
});

Route::post('/orders/{order}/respond', [OrderController::class, 'respondToOrder'])
    ->middleware(['auth'])
    ->name('orders.respond');


use App\Http\Controllers\MessageController;

Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

Route::middleware('auth')->get('/orders/{order}/messages', function (\App\Models\Order $order) {
    return \App\Models\Message::with('sender')->where('order_id', $order->id)->get();
})->name('orders.messages');


Route::get('/messages/index', [MessageController::class, 'index'])->name('messages.index');



Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
        Route::delete('/delete/user/{user}', [AdminController::class, 'usersDestroy'])->name('users.destroy');

        // Projects
        Route::get('/projects', [AdminController::class, 'projectsIndex'])->name('projects.index');
        Route::delete('/delete/projects/{project}', [AdminController::class, 'projectsDestroy'])->name('projects.destroy');

        Route::post('/projects/{project}/approve', [AdminController::class, 'approveProject'])->name('projects.approve');
        Route::post('/projects/{project}/reject', [AdminController::class, 'rejectProject'])->name('projects.reject');

        Route::get('/job-ads', [AdminController::class, 'jobAdsIndex'])->name('jobAds.index');
        Route::delete('/job-ads/{jobAd}', [AdminController::class, 'jobAdsDestroy'])->name('jobAds.destroy');
        
        Route::post('/job-ads/{jobAd}/approve', [AdminController::class, 'approveJobAd'])->name('jobAds.approve');
        Route::post('/job-ads/{jobAd}/reject', [AdminController::class, 'rejectJobAd'])->name('jobAds.reject');

        Route::get('/projects/moderation', [AdminController::class, 'moderateProjects'])->name('projects.moderation');
        Route::get('/job-ads/moderation', [AdminController::class, 'moderateJobAds'])->name('jobAds.moderation');
        
        Route::get('/freelancers/moderation', [AdminController::class, 'moderateFreelancers'])->name('freelancers.moderation');
        Route::post('/freelancers/{freelancer}/approve', [AdminController::class, 'approveFreelancer'])->name('freelancers.approve');
        Route::delete('/freelancers/{freelancer}/reject', [AdminController::class, 'rejectFreelancer'])->name('freelancers.reject');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/avatar/get', [PhotoController::class, 'getAvatar'])->name('avatar.get');
    });

require __DIR__.'/auth.php';
