<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SponsorApplicationController;
use App\Http\Controllers\Api\SponsorController;
use App\Http\Controllers\Api\DealTypeController;
use App\Http\Controllers\Api\DealController;
use App\Http\Controllers\Api\DeliverTypeController;
use App\Http\Controllers\Api\DeliverableController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\TeamController;
use  App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ReportController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);
Route::post(
    '/forgot-password',
    [AuthController::class, 'forgotPassword']
);

Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::post(
    '/sponsor-request',
    [SponsorApplicationController::class, 'store']
);

Route::middleware([ 'auth:api','admin'])->group(function () {

    Route::post(
        '/approve-sponsor',
        [SponsorController::class, 'approveSponsor']
    );

    Route::post(
        '/sponsor-status',
        [SponsorController::class, 'sponsorStatus']
    );


    Route::post(
        '/add-sponsor',
        [SponsorController::class, 'addSponsor']
    );


    Route::post(
        '/delete-sponsor',
        [SponsorController::class, 'deleteSponsor']
    );




    Route::post(
        '/update-sponsor',
        [SponsorController::class, 'updateSponsor']
    );

    Route::get('/sponsors',[SponsorController::class, 'index']
);


    Route::post(
        '/deal-type',
        [DealTypeController::class, 'store']
    );
    Route::post(
        '/deal',
        [DealController::class, 'store']
    );


    Route::put(
        '/update-deal/{id}',
        [DealController::class, 'updateDeal']
    );

    Route::get(
    '/deals',
    [DealController::class, 'index']
);


    Route::post(
        '/deliver-type',
        [DeliverTypeController::class, 'store']
    );


    Route::post(
        '/deliverable',
        [DeliverableController::class, 'store']
    );

    Route::post(
        '/attachments',
        [AttachmentController::class, 'store']
    );

    Route::delete(
        '/deliverables/{id}',
        [DeliverableController::class, 'delete']
    );


    Route::post(
        '/deliverables/{id}',
        [DeliverableController::class, 'update']
    );
    

    Route::post(
        '/invoices',
        [InvoiceController::class, 'store']
    );


    Route::put(
        '/invoices/{id}',
        [InvoiceController::class, 'update']
    );

    Route::delete(
        '/invoices/{id}',
        [InvoiceController::class, 'destroy']
    );

Route::get(
    '/invoices',
    [InvoiceController::class, 'index']
);

Route::get(
    '/invoices/{id}',
    [InvoiceController::class, 'show']
);




    Route::post(
        '/campaigns',
        [CampaignController::class, 'store']
    );

Route::post(
    '/campaigns/{dealId}/update',
    [CampaignController::class, 'update']
);


Route::delete(
    '/campaigns/{dealId}',
    [CampaignController::class, 'delete']
);
Route::get(
    '/deliverables',
    [DeliverableController::class, 'index']
);


Route::post(
    '/teams',
    [TeamController::class, 'store']
);

Route::put(
    '/teams/{id}',
    [TeamController::class, 'update']
);


Route::delete(
    '/teams/{id}',
    [TeamController::class, 'destroy']
);


Route::get(
    '/teams',
    [TeamController::class, 'index']
);

Route::post(
    '/tickets',
    [TicketController::class, 'store']
);


Route::post(
    '/tickets/update/{id}',
    [TicketController::class, 'update']
);

Route::delete(
    '/tickets/{id}',
    [TicketController::class, 'delete']
);

Route::get(
    '/tickets',
    [TicketController::class, 'index']
);

Route::get(
    '/tickets/{id}',
    [TicketController::class, 'show']
);

// Admin Reports
    Route::get(
        '/reports',
        [ReportController::class,'index']
    );

    Route::get(
        '/reports/{id}',
        [ReportController::class,'show']
    );

  Route::patch(
    '/reports/status/{id}',
    [ReportController::class,'updateStatus']
);

   Route::delete(
    '/reports/{id}',
    [ReportController::class,'delete']
);

});




Route::middleware(
    'auth:api'
)->group(function () {

    Route::get(
        '/sponsor/deliverables',
        [
            DeliverableController::class,
            'sponsorDeliverables'
        ]
    );

      Route::get(
            '/sponsor/tickets',
            [TicketController::class, 'sponsorTickets']
        );

        Route::patch(
    '/sponsor/tickets/{id}/status',
    [TicketController::class, 'updateSponsorTicketStatus']
);
    // Sponsor Reports

   Route::post(
        '/reports',
        [ReportController::class, 'store']
    );

        Route::get(
        '/sponsor/reports',
        [ReportController::class,'sponsorIndex']
    );

    Route::get(
        '/sponsor/reports/{id}',
        [ReportController::class,'sponsorShow']
    );

   Route::delete(
    '/sponsor/reports/{id}',
    [ReportController::class,'sponsorDelete']
);
    



});
