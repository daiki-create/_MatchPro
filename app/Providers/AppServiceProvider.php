<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\ContentRepositoryInterface::class,
            \App\Repositories\ContentRepository::class,
        );
        $this->app->bind(
            \App\Repositories\TempCoachRepositoryInterface::class,
            \App\Repositories\TempCoachRepository::class,
        );
        $this->app->bind(
            \App\Repositories\CoachRepositoryInterface::class,
            \App\Repositories\CoachRepository::class,
        );
        $this->app->bind(
            \App\Repositories\ReservationRepositoryInterface::class,
            \App\Repositories\ReservationRepository::class,
        );
        $this->app->bind(
            \App\Repositories\MessageRepositoryInterface::class,
            \App\Repositories\MessageRepository::class,
        );
        $this->app->bind(
            \App\Repositories\VerificationDocumentRepositoryInterface::class,
            \App\Repositories\VerificationDocumentRepository::class,
        );
        $this->app->bind(
            \App\Repositories\PayrollAccountRepositoryInterface::class,
            \App\Repositories\PayrollAccountRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
