<?php

namespace App\Providers;

use App\Interfaces\AuthRepositoryInterface;
use App\Repositories\AuthRepository;

use App\Interfaces\SponsorApplicationRepositoryInterface;
use App\Repositories\SponsorApplicationRepository;

use Illuminate\Support\ServiceProvider;

use App\Repositories\DeliverTypeRepository;
use App\Interfaces\DeliverTypeRepositoryInterface;

use App\Repositories\DeliverableRepository;
use App\Interfaces\DeliverableRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
        );

        $this->app->bind(
            SponsorApplicationRepositoryInterface::class,
            SponsorApplicationRepository::class
        );

            $this->app->bind(
        AuthRepositoryInterface::class,
        AuthRepository::class
    );

    $this->app->bind(
        SponsorApplicationRepositoryInterface::class,
        SponsorApplicationRepository::class
    );


    $this->app->bind(
    \App\Interfaces\DealTypeRepositoryInterface::class,
    \App\Repositories\DealTypeRepository::class
);


$this->app->bind(
    \App\Interfaces\DealRepositoryInterface::class,
    \App\Repositories\DealRepository::class
);


$this->app->bind(
    DeliverableRepositoryInterface::class,
    DeliverableRepository::class
);


$this->app->bind(
    DeliverTypeRepositoryInterface::class,
    DeliverTypeRepository::class
);
    }

    public function boot(): void
    {
        //
    }



}