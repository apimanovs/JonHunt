<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->loadMissing([
            'jobApplication.jobAd',
            'projectApplication.project',
            'client',
            'freelancer',
        ]);

        return inertia('Orders/ShowOrder', [
            'order' => $order,
        ]);
    }


    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:in_progress,completed',
        ]);

        if (in_array($order->status, ['cancelled', 'completed'])) {
            return back()->withErrors(['message' => 'Order is already completed or cancelled.']);
        }

        if ($validated['status'] === 'completed') {

            $order->status = 'completed';
            $order->save();

            return back()->with('success', 'Order completed!');
        }

        $order->status = $validated['status'];
        $order->save();

        return back()->with('success', 'Order status updated!');
    }

    public function respondToOrder(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return back()->withErrors(['message' => 'This order is no longer available for response.']);
        }

        $validated = $request->validate([
            'action' => 'required|in:accept,decline',
        ]);

        if ($validated['action'] === 'accept') {
            $order->status = 'in_progress';
            $order->save();

            return back()->with('success', 'You have accepted the order. Work can now begin.');
        }

        if ($validated['action'] === 'decline') {
            $clientBalance = $order->client->balance;

            if (!$clientBalance) {
                return back()->withErrors(['message' => 'Client balance not found.']);
            }

            $price = $this->getOrderPrice($order);
            $clientBalance->amount += $price;
            $clientBalance->save();

            $order->status = 'cancelled';
            $order->cancel_reason = 'Freelancer declined the order';
            $order->save();

            return back()->with('success', 'You have declined the order. Funds returned to the client.');
        }

        return back()->withErrors(['message' => 'Invalid action.']);
    }


    public function completeOrder(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'submitted') {
            return back()->withErrors(['message' => 'Order cannot be completed in its current status.']);
        }

        $freelancerBalance = $order->freelancer->balance;

        if (!$freelancerBalance) {
            return back()->withErrors(['message' => 'Freelancer balance not found.']);
        }

        $price = $this->getOrderPrice($order);
        $freelancerBalance->amount += $price;
        $freelancerBalance->save();

        $order->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Order has been completed successfully. Payment has been sent to the freelancer.');
    }



    public function submitWork(Request $request, Order $order)
    {
        $this->authorize('update', $order);
    
        if (!in_array($order->status, ['in_progress', 'submitted'])) {
            return back()->withErrors(['message' => 'You can only submit or update work while the order is in progress or under review.']);
        }
    
        $validated = $request->validate([
            'result_text' => 'required|string',
            'result_file' => 'nullable|file|max:10240',
        ]);
    
        $filePath = $order->result_file;
    
        if ($request->hasFile('result_file')) {
            if ($filePath) {
                $parsedUrl = parse_url($filePath);
                $path = $parsedUrl['path'] ?? null;
    
                if ($path) {
                    $path = ltrim($path, '/');
                    $publicId = preg_replace('/\.[^.]+$/', '', $path);
    
                    try {
                        Cloudinary::destroy($publicId);
                    } catch (\Exception $e) {
                    }
                }
            }
    
            $uploadedFile = $request->file('result_file');
            $cloudinaryResult = Cloudinary::uploadFile($uploadedFile->getRealPath(), [
                'folder' => 'order-results/' . $order->id,
            ]);
    
            $filePath = $cloudinaryResult->getSecurePath();
        }
    
        $order->update([
            'result_text' => $validated['result_text'],
            'result_file' => $filePath,
            'status' => 'submitted',
        ]);
    
        return back()->with('success', 'Work submitted successfully. The client will review it.');
    }

    private function getOrderPrice(Order $order)
    {
        if ($order->jobApplication && $order->jobApplication->jobAd) {
            return $order->jobApplication->jobAd->Price;
        }

        if ($order->projectApplication && $order->projectApplication->project) {
            return $order->projectApplication->project->budget;
        }

        return 0;
    }


    public function index(Request $request)
    {
        $user = $request->user();

        $ordersAsClient = $user->ordersAsClient()
        ->with(['jobApplication.jobAd', 'projectApplication.project', 'freelancer'])
        ->get();

        $ordersAsFreelancer = $user->ordersAsFreelancer()
        ->with(['jobApplication.jobAd', 'projectApplication.project', 'client'])
        ->get();

        return inertia('Orders/Index', [
            'ordersAsClient' => $ordersAsClient,
            'ordersAsFreelancer' => $ordersAsFreelancer,
        ]);
    }
}
