<?php

namespace Digikraaft\PaystackCustomersTile;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class PaystackCustomersTileServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FetchCustomersDataFromPaystackApi::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/paystack-customers-tile'),
        ], 'paystack-customers-tile-views');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dashboard-paystack-customers-tile');

        Livewire::component('paystack-customers-tile', PaystackCustomersTileComponent::class);
    }
}
