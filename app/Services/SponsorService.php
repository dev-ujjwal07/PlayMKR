<?php

namespace App\Services;

use Exception;
use App\Interfaces\SponsorApplicationRepositoryInterface;

class SponsorService
{
    protected $sponsorRepository;

    public function __construct(
        SponsorApplicationRepositoryInterface $sponsorRepository
    )
    {
        $this->sponsorRepository = $sponsorRepository;
    }

    public function approveSponsor(array $data)
    {
        $application = $this->sponsorRepository
            ->findApplicationById($data['id']);

        if (!$application) {
            throw new Exception(
                'Sponsor application not found'
            );
        }

        $this->sponsorRepository
            ->updateApplicationStatus(
                $data['id'],
                $data['status']
            );

        if ($data['status'] === 'approved') {

            return $this->sponsorRepository
                ->createSponsor([
                    'name' => $application->name,
                    'email' => $application->email,
                    'contact_number' => $application->contact_number,
                    'website_url' => $application->website_url,
                    'industry' => $application->industry,
                    'address' => $application->address,
                ]);
        }

        return $application;
    }
}