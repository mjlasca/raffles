<?php

namespace App\Services;

use App\Mail\RequestsNotificationPermission;
use App\Models\Delivery;
use App\Models\DeliveryPermission;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RequestPermissionService
{
    public function createRequest($user, $deliveryId)
    {
        $requests =  DeliveryPermission::create([
            'user_requests' => $user->id,
            'delivery_id' => $deliveryId,
            'status' => 0
        ]);
        $this->sendNotification($user->email, $requests);
    }

    protected function sendNotification($email, DeliveryPermission $deliveryPermission)
    {
        Mail::to($email)->send(
            new RequestsNotificationPermission($deliveryPermission)
        );
    }

    public function allowPermission(Delivery $delivery, User $user){
        return DeliveryPermission::where('user_requests',$user->id)->where('delivery_id', $delivery->id)->first();
    }

    public function hasPendingRequest($userId, $registroId)
    {
        return DeliveryPermission::where('user_id', $userId)
            ->where('status', 'pendiente')
            ->exists();
    }
}
