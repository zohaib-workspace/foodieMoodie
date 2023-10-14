<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Auth;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
          Auth::extend('delivery_men', function ($app, $name, array $config) {
                $provider = new EloquentUserProvider($app['hash'], $config['model']);
                return new PassportGuard($provider, $app['request']);
            });
        Passport::routes();
        
        //
    }
}
