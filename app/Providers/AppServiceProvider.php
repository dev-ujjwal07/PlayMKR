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

use App\Repositories\AttachmentRepository;
use App\Interfaces\AttachmentRepositoryInterface;

use App\Repositories\InvoiceRepository;
use App\Interfaces\InvoiceRepositoryInterface;

use App\Repositories\CampaignRepository;
use App\Interfaces\CampaignRepositoryInterface;

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

$this->app->bind(
    AttachmentRepositoryInterface::class,
    AttachmentRepository::class
);

$this->app->bind(
    InvoiceRepositoryInterface::class,
    InvoiceRepository::class
);

$this->app->bind(
    CampaignRepositoryInterface::class,
    CampaignRepository::class
);
    }

    public function boot(): void
    {
        //
    }



}