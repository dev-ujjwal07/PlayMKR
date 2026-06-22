<?php

namespace App\Services;

use Exception;
use App\Interfaces\SponsorApplicationRepositoryInterface;
use App\Exceptions\SponsorAlreadyApprovedException;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SponsorCredentialsMail;


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

    $existingSponsor = $this->sponsorRepository
        ->findSponsorByEmail(
            $application->email
        );

    if ($existingSponsor) {

        throw new SponsorAlreadyApprovedException(
            'Sponsor already approved'
        );
    }

    $plainPassword =
        Str::random(10);

    $sponsor =
        $this->sponsorRepository
            ->createSponsor([

                'name' =>
                    $application->name,

                'email' =>
                    $application->email,

                'password' =>
                    Hash::make(
                        $plainPassword
                    ),

                'contact_number' =>
                    $application->contact_number,

                'website_url' =>
                    $application->website_url,

                'industry' =>
                    $application->industry,

                'address' =>
                    $application->address,
            ]);

    $this->sponsorRepository
        ->createUser([

            'name' =>
                $sponsor->name,

            'email' =>
                $sponsor->email,

            'password' =>
                $plainPassword
        ]);

    Mail::to(
        $sponsor->email
    )->send(
        new SponsorCredentialsMail(
            $sponsor->email,
            $plainPassword
        )
    );

    return $sponsor;
}
        return $application;
    }



    public function updateSponsorStatus(
    array $data
)
{
    $sponsor = $this->sponsorRepository
        ->findSponsorById(
            $data['id']
        );

    if (!$sponsor) {

        throw new Exception(
            'Sponsor not found'
        );
    }

    $this->sponsorRepository
        ->updateSponsorStatus(
            $data['id'],
            $data['status']
        );

    $sponsor->status = $data['status'];

    return $sponsor;
}


public function addSponsor(array $data)
{
    $existingSponsor = $this->sponsorRepository
        ->findSponsorByEmail(
            $data['email']
        );

    if ($existingSponsor) {

        throw new Exception(
            'Sponsor email already exists'
        );
    }

    $plainPassword = Str::random(10);

    $sponsor = $this->sponsorRepository
        ->createDirectSponsor([

            'name' => $data['name'],

            'email' => $data['email'],

            'password' => Hash::make(
                $plainPassword
            ),

            'contact_number' =>
                $data['contact_number'],

            'website_url' =>
                $data['website_url'],

            'industry' =>
                $data['industry'],

            'address' =>
                $data['address'],

            'status' =>
                $data['status']
        ]);

$this->sponsorRepository
    ->createUser([

        'name' =>
            $sponsor->name,

        'email' =>
            $sponsor->email,

        'password' =>
            $plainPassword
    ]);





    Mail::to($sponsor->email)
        ->send(
            new SponsorCredentialsMail(
                $sponsor->email,
                $plainPassword
            )
        );

    return $sponsor;
}


public function deleteSponsor(int $id)
{
    $sponsor = $this->sponsorRepository
        ->findSponsorById($id);

    if (!$sponsor) {

        throw new Exception(
            'Sponsor not found'
        );
    }

    $this->sponsorRepository
        ->deleteSponsor($id);

    return true;
}




public function updateSponsor(
    array $data
)
{
    $sponsor = $this->sponsorRepository
        ->findSponsorById(
            $data['id']
        );

    if (!$sponsor) {

        throw new Exception(
            'Sponsor not found'
        );
    }

    return $this->sponsorRepository
        ->updateSponsor(
            $data['id'],
            $data
        );
}

}