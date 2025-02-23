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

        $order->load('jobApplication', 'jobApplication.jobAd', 'client', 'freelancer');
        
        return inertia('Orders/ShowOrder', [
            'order' => $order,
        ]);
    }


public function updateStatus(Request $request, Order $order)
{
    $this->authorize('update', $order);

    $validated = $request->validate([
        'status' => 'required|in:in_progress,completed,cancelled',
        'cancel_reason' => 'required_if:status,cancelled|string|nullable|max:1000',
    ]);

    if (in_array($order->status, ['cancelled', 'completed'])) {
        return back()->withErrors(['message' => 'Order is already completed or cancelled.']);
    }

    if ($validated['status'] === 'cancelled') {
        $order->cancel_reason = $validated['cancel_reason'];

        $clientBalance = $order->client->balance;
        if (!$clientBalance) {
            return back()->withErrors(['message' => 'Client balance not found.']);
        }
        $price = $order->jobApplication->jobAd->Price;
        $clientBalance->amount += $price;
        $clientBalance->save();

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order cancelled. Funds have been refunded.');
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

public function completeOrder(Request $request, Order $order)
{
    $this->authorize('update', $order);

    if ($order->status !== 'submitted') {
        return back()->withErrors(['message' => 'Order cannot be completed in its current status.']);
    }

    $order->update([
        'status' => 'completed',
    ]);

    return back()->with('success', 'Order has been completed successfully.');
}


public function submitWork(Request $request, Order $order)
{
    $this->authorize('update', $order);

    if ($order->status !== 'in_progress') {
        return back()->withErrors(['message' => 'Order cannot be completed in its current status.']);
    }

    $validated = $request->validate([
        'result_text' => 'required|string',
        'result_file' => 'nullable|file|max:10240', // Ограничение на размер файла 10 MB
    ]);

    $filePath = null;
    if ($request->hasFile('result_file')) {
        $uploadedFile = $request->file('result_file');
        $cloudinaryResult = Cloudinary::uploadFile($uploadedFile->getRealPath(), [
            'folder' => 'order-results/' . $order->id, // Папка на Cloudinary
        ]);

        $filePath = $cloudinaryResult->getSecurePath(); // Получение публичной ссылки на файл
    }

    $order->update([
        'result_text' => $validated['result_text'],
        'result_file' => $filePath, // Ссылка на файл из Cloudinary
        'status' => 'submitted', // Новый статус заказа
    ]);

    return back()->with('success', 'Work submitted successfully. The client will review it.');
}

    public function index(Request $request)
    {
        $user = $request->user();


        $ordersAsClient = $user->ordersAsClient()
            ->with(['jobApplication.jobAd', 'freelancer'])
            ->get();

        $ordersAsFreelancer = $user->ordersAsFreelancer()
            ->with(['jobApplication.jobAd', 'client'])
            ->get();

        return inertia('Orders/Index', [
            'ordersAsClient' => $ordersAsClient,
            'ordersAsFreelancer' => $ordersAsFreelancer,
        ]);
    }
}
