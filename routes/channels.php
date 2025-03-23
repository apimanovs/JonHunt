<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    $order = \App\Models\Order::find($orderId);

    if (!$order) {
        Log::debug("Order {$orderId} not found.");
        return false;
    }

    $authorized = ($user->id === $order->client_id || $user->id === $order->freelancer_id);

    if (!$authorized) {
        Log::debug("User {$user->id} NOT authorized for order {$orderId} (client: {$order->client_id}, freelancer: {$order->freelancer_id})");
    } else {
        Log::debug("User {$user->id} IS authorized for order {$orderId}");
    }

    return $authorized;
});
