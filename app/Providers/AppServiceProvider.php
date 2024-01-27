<?php

namespace App\Providers;

use App\Models\Delivery;
use App\Models\Prize;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('unique_user', function ($attribute, $value, $parameters, $validator) {
            $exists = User::where('email', $value)->exists();
            return !$exists;
        });

        Validator::extend('tickets_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = Ticket::whereIn('ticket_number', explode("\n", $value))->exists();
            return !$exists;
        });

        Validator::extend('jackpot', function ($attribute, $value, $parameters, $validator) {
            $exists = Prize::where('raffle_id', $parameters[0])->where('type',$value)->exists();
            return !$exists;
        });

        Validator::extend('delivery_total', function ($attribute, $value, $parameters, $validator) {
            $data = explode(":",$parameters[0]) ?? null;
            
            $raffle_id = $data[0];
            $user = $data[1];
            
            $sum = Delivery::where('raffle_id',$raffle_id)->where('user_id',$user)->sum('total') + $value;
            $sumTicket = Ticket::where('raffle_id',$raffle_id)->where('user_id',$user)->sum('price');
            $sumPayment = Ticket::where('raffle_id',$raffle_id)->where('user_id',$user)->sum('payment');

            $sumTotal = ($sumTicket - $sumPayment );
            
            return $sumTotal >= $sum;
        });

        Validator::extend('length_ticket', function ($attribute, $value, $parameters, $validator) {
            $requiredLength = $parameters[0] ?? null;
            $invalidValues = [];
            foreach (explode("\n", $value) as $val) {
                if ($val >= $requiredLength) {
                    $invalidValues[] = $val;
                }
            }
            return empty($invalidValues);
        });
    }
}
