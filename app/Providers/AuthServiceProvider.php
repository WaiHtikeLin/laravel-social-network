<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
//use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    { 
        Gate::define('viewWebSocketsDashboard', function ($user = null) {
        return in_array($user->email, [
            'admin@gmail.com',
            'waihtikelin279@gmail.com'
          ]);
        });

        //Passport::routes();
        //
    }
}
