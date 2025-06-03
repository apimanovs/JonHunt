<?php

namespace App\Http\Controllers;

use App\Models\JobAdvertisement;
use App\Models\JobApplication;
use App\Models\Order;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewOrderNotificationMail;

class JobApplicationController extends Controller
{
    public function create($jobAdId)
    {
        $jobAd = JobAdvertisement::findOrFail($jobAdId);

        return inertia('JobApplication', [
            'jobAd' => $jobAd,
        ]);
    }

    public function store(Request $request, $jobAdId)
    {
        $request->validate([
            'requirements' => 'required|string|min:10',
        ]);

        $jobAd = JobAdvertisement::findOrFail($jobAdId);

        $total = $jobAd->Price;

        $userBalance = auth()->user()->balance;
        if (!$userBalance) {
            return back()->withErrors(['message' => 'Balance of a user is not found']);
        }

        if ($userBalance->amount < $total) {
            return back()->withErrors(['message' => 'No enough money on the balance']);
        }

        $userBalance->amount -= $total;
        $userBalance->save();

        $jobApplication = JobApplication::create([
            'job_ad_id'    => $jobAd->id,
            'user_id'      => auth()->id(),
            'requirements' => $request->input('requirements'),
        ]);


        $order = Order::create([
            'job_application_id' => $jobApplication->id,
            'client_id' => auth()->id(),           
            'freelancer_id' => $jobAd->creator_id, 
            'status' => 'pending',
            'project_application_id' => null,
        ]);
        

        $freelancer = \App\Models\User::find($jobAd->creator_id);

        Mail::to($freelancer->email)->send(new NewOrderNotificationMail($order));

        return redirect()->route('orders.index')
            ->with('success', 'Order created and payment processed successfully! You have to wait until the freelancer will accept your application');
    }
}
