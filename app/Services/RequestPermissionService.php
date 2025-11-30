<?php

namespace App\Services;

use App\Mail\AllowRequestsNotificationPermission;
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
        $users = User::where('role', 'Administrador')->where('enabled', 1)->get();
        $emails = [];
        foreach ($users as $user) {
            $emails[] = $user->email;
        }
        $this->sendNotification($emails, $requests);
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

    public function hasPendingRequest($id, $user, $date)
    {
        DeliveryPermission::where('id',$id)->update([
            'date_permission' => $date,
            'status' => 1,
            'allow_user' => $user->id
        ]);
        $deliveryPermission = DeliveryPermission::where('id',$id)->first();
        $this->sendAllowNotification($deliveryPermission->urequests->email, $deliveryPermission);
    }

    protected function sendAllowNotification($email, DeliveryPermission $deliveryPermission)
    {
        Mail::to($email)->send(
            new AllowRequestsNotificationPermission($deliveryPermission)
        );
    }
}
