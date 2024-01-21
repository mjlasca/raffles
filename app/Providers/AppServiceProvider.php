<?php

namespace App\Providers;

use App\Models\Ticket;
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
        Validator::extend('tickets_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = Ticket::whereIn('ticket_number', explode("\n", $value))->exists();
            return !$exists;
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
