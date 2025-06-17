<?php

namespace App\Providers;

use App\Http\Repositories\CallLogRepository\CallLogInterface;
use App\Http\Repositories\CallLogRepository\CallLogRepository;
use App\Http\Repositories\ContactRepository\ContactInterface;
use App\Http\Repositories\ContactRepository\ContactRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ContactInterface::class, ContactRepository::class);
        $this->app->bind(CallLogInterface::class, CallLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
