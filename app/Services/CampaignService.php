<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Constants\CampaignConstants;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CampaignRepositoryInterface;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class CampaignService
{
    protected $campaignRepository;

    public function __construct(
        CampaignRepositoryInterface $campaignRepository
    )
    {
        $this->campaignRepository =
            $campaignRepository;
    }

    public function create(
        array $data,
        array $files = []
    )
    {
        return DB::transaction(
            function () use (
                $data,
                $files
            ) {

                $dealType =
                    $this->campaignRepository
                        ->findDealTypeByName(
                            $data['deal']['deal_type_name']
                        );

                if (!$dealType) {

                    throw new Exception(
                        CampaignConstants::DEAL_TYPE_NOT_FOUND
                    );
                }

                $sponsor =
                    $this->campaignRepository
                        ->findSponsorById(
                            $data['deal']['sponsor_id']
                        );

                if (!$sponsor) {

                    throw new Exception(
                        CampaignConstants::SPONSOR_NOT_FOUND
                    );
                }

                $deal =
                    $this->campaignRepository
                        ->createDeal([

                            'sponsor_id' =>
                                $data['deal']['sponsor_id'],

                            'deal_title' =>
                                $data['deal']['deal_title'],

                            'deal_description' =>
                                $data['deal']['deal_description'],

                            'status' =>
                                $data['deal']['status'],

                            'total_deal_value' =>
                                $data['deal']['total_deal_value'],

                            'deal_type_id' =>
                                $dealType->id
                        ]);

                foreach (
                    $data['deliverables']
                    as $index => $deliverable
                ) {

                    $deliverType =
                        $this->campaignRepository
                            ->findDeliverTypeByName(
                                $deliverable[
                                    'deliver_type_name'
                                ]
                            );

                    if (!$deliverType) {

                        throw new Exception(
                            CampaignConstants::DELIVER_TYPE_NOT_FOUND
                        );
                    }

                    $createdDeliverable =
                        $this->campaignRepository
                            ->createDeliverable([

                                'deal_id' =>
                                    $deal->id,

                                'deliver_type_id' =>
                                    $deliverType->id,

                                'title' =>
                                    $deliverable['title'],

                                'description' =>
                                    $deliverable['description']
                                    ?? null,

                                'quantity' =>
                                    $deliverable['quantity'],

                                'sponsor_id' =>
                                    $deal->sponsor_id,

                                'assigned_to' =>
                                    null,

                                'status' =>
                                    'pending',

                                'status_updated_at' =>
                                    Carbon::now(),

                                'distribution_date' =>
                                    null,

                                'priority' =>
                                    $deliverable['priority'],

                                'start_date' =>
                                    $deliverable['start_date'],

                                'due_date' =>
                                    $deliverable['due_date']
                            ]);

                    if (
                        isset($files[$index])
                    ) {

                        foreach (
                            $files[$index]
                            as $file
                        ) {

                            $path =
                                $file->store(
                                    'attachments',
                                    'public'
                                );

                            $this->campaignRepository
                                ->createAttachment([

                                    'deliverable_id' =>
                                        $createdDeliverable->id,

                                    'attachment' =>
                                        $path
                                ]);
                        }
                    }
                }

                foreach (
                    $data['milestones']
                    as $milestone
                ) {

                    $this->campaignRepository
                        ->createMilestone([

                            'deal_id' =>
                                $deal->id,

                            'name' =>
                                $milestone['name'],

                            'due_date' =>
                                $milestone['due_date'],

                            'status' =>
                                $milestone['status']
                        ]);
                }
                                foreach (
                    $data['invoices']
                    as $invoice
                ) {

                    $lastInvoice =
                        Invoice::latest('id')
                            ->first();

                    $number =
                        $lastInvoice
                        ? $lastInvoice->id + 1
                        : 1;

                    $invoiceId =
                        'INV-' .
                        str_pad(
                            $number,
                            4,
                            '0',
                            STR_PAD_LEFT
                        );

                    $this->campaignRepository
                        ->createInvoice([

                            'deal_id' =>
                                $deal->id,

                            'sponsor_id' =>
                                $deal->sponsor_id,

                            'invoice_id' =>
                                $invoiceId,

                            'invoice_title' =>
                                null,

                            'invoice_amount' =>
                                $invoice[
                                    'invoice_amount'
                                ],

                            'payment_type' =>
                                $invoice[
                                    'payment_type'
                                ],

                            'tax' =>
                                null,

                            'discount' =>
                                null,

                            'total_amount' =>
                                null,

                            'currency' =>
                                null,

                            'invoice_date' =>
                                $invoice[
                                    'invoice_date'
                                ],

                            'due_date' =>
                                $invoice[
                                    'due_date'
                                ],

                            'payment_status' =>
                                $invoice[
                                    'payment_status'
                                ],

                            'billing_address' =>
                                null,

                            'contact_email' =>
                                null
                        ]);
                }

                return $deal;
            }
        );
    }






    public function update(
    int $dealId,
    array $data,
    array $files = []
)
{
    return DB::transaction(
        function () use (
            $dealId,
            $data,
            $files
        ) {

            $deal =
                $this->campaignRepository
                    ->findDealById(
                        $dealId
                    );

            if (!$deal) {

                throw new Exception(
                    CampaignConstants::CAMPAIGN_NOT_FOUND
                );
            }

            $dealType =
                $this->campaignRepository
                    ->findDealTypeByName(
                        $data['deal']['deal_type_name']
                    );

            $sponsor =
                $this->campaignRepository
                    ->findSponsorById(
                        $data['deal']['sponsor_id']
                    );

            $oldDeliverables =
                $this->campaignRepository
                    ->getDeliverablesByDealId(
                        $dealId
                    );

            foreach (
                $oldDeliverables
                as $oldDeliverable
            ) {

                $attachments =
                    Attachment::where(
                        'deliverable_id',
                        $oldDeliverable->id
                    )->get();

                foreach (
                    $attachments
                    as $attachment
                ) {

                    Storage::disk('public')
                        ->delete(
                            $attachment->attachment
                        );
                }
            }

            $this->campaignRepository
                ->deleteDeliverables(
                    $dealId
                );

            $this->campaignRepository
                ->deleteMilestones(
                    $dealId
                );

            $this->campaignRepository
                ->deleteInvoices(
                    $dealId
                );

            $deal =
                $this->campaignRepository
                    ->updateDeal(
                        $dealId,
                        [

                            'sponsor_id' =>
                                $data['deal']['sponsor_id'],

                            'deal_title' =>
                                $data['deal']['deal_title'],

                            'deal_description' =>
                                $data['deal']['deal_description'],

                            'status' =>
                                $data['deal']['status'],

                            'total_deal_value' =>
                                $data['deal']['total_deal_value'],

                            'deal_type_id' =>
                                $dealType->id
                        ]
                    );


foreach (
                    $data['deliverables']
                    as $index => $deliverable
                ) {

                    $deliverType =
                        $this->campaignRepository
                            ->findDeliverTypeByName(
                                $deliverable[
                                    'deliver_type_name'
                                ]
                            );

                    if (!$deliverType) {

                        throw new Exception(
                            CampaignConstants::DELIVER_TYPE_NOT_FOUND
                        );
                    }

                    $createdDeliverable =
                        $this->campaignRepository
                            ->createDeliverable([

                                'deal_id' =>
                                    $deal->id,

                                'deliver_type_id' =>
                                    $deliverType->id,

                                'title' =>
                                    $deliverable['title'],

                                'description' =>
                                    $deliverable['description']
                                    ?? null,

                                'quantity' =>
                                    $deliverable['quantity'],

                                'sponsor_id' =>
                                    $deal->sponsor_id,

                                'assigned_to' =>
                                    null,

                                'status' =>
                                    'pending',

                                'status_updated_at' =>
                                    Carbon::now(),

                                'distribution_date' =>
                                    null,

                                'priority' =>
                                    $deliverable['priority'],

                                'start_date' =>
                                    $deliverable['start_date'],

                                'due_date' =>
                                    $deliverable['due_date']
                            ]);

                    if (
                        isset($files[$index])
                    ) {

                        foreach (
                            $files[$index]
                            as $file
                        ) {

                            $path =
                                $file->store(
                                    'attachments',
                                    'public'
                                );

                            $this->campaignRepository
                                ->createAttachment([

                                    'deliverable_id' =>
                                        $createdDeliverable->id,

                                    'attachment' =>
                                        $path
                                ]);
                        }
                    }
                }

                foreach (
                    $data['milestones']
                    as $milestone
                ) {

                    $this->campaignRepository
                        ->createMilestone([

                            'deal_id' =>
                                $deal->id,

                            'name' =>
                                $milestone['name'],

                            'due_date' =>
                                $milestone['due_date'],

                            'status' =>
                                $milestone['status']
                        ]);
                }
                                foreach (
                    $data['invoices']
                    as $invoice
                ) {

                    $lastInvoice =
                        Invoice::latest('id')
                            ->first();

                    $number =
                        $lastInvoice
                        ? $lastInvoice->id + 1
                        : 1;

                    $invoiceId =
                        'INV-' .
                        str_pad(
                            $number,
                            4,
                            '0',
                            STR_PAD_LEFT
                        );

                    $this->campaignRepository
                        ->createInvoice([

                            'deal_id' =>
                                $deal->id,

                            'sponsor_id' =>
                                $deal->sponsor_id,

                            'invoice_id' =>
                                $invoiceId,

                            'invoice_title' =>
                                null,

                            'invoice_amount' =>
                                $invoice[
                                    'invoice_amount'
                                ],

                            'payment_type' =>
                                $invoice[
                                    'payment_type'
                                ],

                            'tax' =>
                                null,

                            'discount' =>
                                null,

                            'total_amount' =>
                                null,

                            'currency' =>
                                null,

                            'invoice_date' =>
                                $invoice[
                                    'invoice_date'
                                ],

                            'due_date' =>
                                $invoice[
                                    'due_date'
                                ],

                            'payment_status' =>
                                $invoice[
                                    'payment_status'
                                ],

                            'billing_address' =>
                                null,

                            'contact_email' =>
                                null
                        ]);
                }

                return $deal;
            }
        );
    }






    public function delete(
    int $dealId
)
{
    return DB::transaction(
        function () use (
            $dealId
        ) {

            $deal =
                $this->campaignRepository
                    ->findDealById(
                        $dealId
                    );

            if (!$deal) {

                throw new Exception(
                    CampaignConstants::CAMPAIGN_NOT_FOUND
                );
            }

            $deliverables =
                $this->campaignRepository
                    ->getDeliverablesByDealId(
                        $dealId
                    );

            foreach (
                $deliverables
                as $deliverable
            ) {

                $attachments =
                    Attachment::where(
                        'deliverable_id',
                        $deliverable->id
                    )->get();

                foreach (
                    $attachments
                    as $attachment
                ) {

                    Storage::disk('public')
                        ->delete(
                            $attachment->attachment
                        );
                }
            }

            $this->campaignRepository
                ->deleteDeal(
                    $dealId
                );

            return true;
        }
    );
}
}