<?php

namespace App\Services;

use Exception;
use App\Interfaces\SponsorApplicationRepositoryInterface;
use App\Exceptions\SponsorAlreadyApprovedException;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SponsorCredentialsMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

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


        $user =
    $this->sponsorRepository
        ->findUserByEmail(
            $sponsor->email
        );

if (!$user) {

    $this->sponsorRepository
        ->createUser([

            'name' =>
                $sponsor->name,

            'email' =>
                $sponsor->email,

            'password' =>
                $plainPassword
        ]);
}







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
        ->updateSponsorProfile(
            $data['id'],
            $data
        );
}



public function getSponsors(
    array $filters
)
{
    return $this->sponsorRepository
        ->getSponsors(
            $filters
        );
}




public function updateProfile(
    array $data
)
{
    return DB::transaction(

        function () use ($data) {

            $authUser =
                request()->user();

            $sponsor =
                $this->sponsorRepository
                    ->getSponsorByEmail(
                        $authUser->email
                    );

            if (!$sponsor) {

                throw new Exception(
                    'Sponsor not found'
                );
            }

            $user =
                User::find(
                    $authUser->id
                );

            if (!$user) {

                throw new Exception(
                    'User not found'
                );
            }

            $profilePath =
                $user->profile;

            if (
                isset($data['profile'])
            ) {

                if (
                    $profilePath &&
                    Storage::disk('public')->exists(
                        $profilePath
                    )
                ) {

                    Storage::disk('public')
                        ->delete(
                            $profilePath
                        );
                }

                $file =
                    $data['profile'];

                $fileName =
                    time() .
                    '_' .
                    $file->getClientOriginalName();

                $profilePath =
                    $file->storeAs(

                        'profiles',

                        $fileName,

                        'public'
                    );
            }

            $userData = [];

            if (
                isset($data['name'])
            ) {

                $userData['name'] =
                    $data['name'];

                $userData['full_name'] =
                    $data['name'];
            }

            if (
                isset($data['email'])
            ) {

                $userData['email'] =
                    $data['email'];
            }

            if (
                isset($data['number'])
            ) {

                $userData['number'] =
                    $data['number'];
            }

            if (
                $profilePath
            ) {

                $userData['profile'] =
                    $profilePath;
            }

            if (!empty($userData)) {

                $this->sponsorRepository
                    ->updateUserById(

                        $user->id,

                        $userData
                    );
            }

            $sponsorData = [];

            if (
                isset($data['name'])
            ) {

                $sponsorData['name'] =
                    $data['name'];
            }

            if (
                isset($data['email'])
            ) {

                $sponsorData['email'] =
                    $data['email'];
            }

            if (
                isset($data['number'])
            ) {

                $sponsorData['contact_number'] =
                    $data['number'];
            }

            if (!empty($sponsorData)) {

                $this->sponsorRepository
                    ->updateSponsorProfile(

                        $sponsor->id,

                        $sponsorData
                    );
            }

                        if (
                isset($data['current_password']) ||
                isset($data['new_password']) ||
                isset($data['confirm_password'])
            ) {

                if (
                    !Hash::check(
                        $data['current_password'],
                        $user->password
                    )
                ) {

                    throw new Exception(
                        'Current password is incorrect.'
                    );
                }

                $password =
                    Hash::make(
                        $data['new_password']
                    );

                /*
                |--------------------------------------------------------------------------
                | Update User Password
                |--------------------------------------------------------------------------
                */

                $this->sponsorRepository
                    ->updateUserById(

                        $user->id,

                        [

                            'password' =>
                                $password
                        ]
                    );

                /*
                |--------------------------------------------------------------------------
                | Update Sponsor Password
                |--------------------------------------------------------------------------
                */

                $this->sponsorRepository
                    ->updateSponsorProfile(

                        $sponsor->id,

                        [

                            'password' =>
                                $password
                        ]
                    );
            }

            return [

                'status' => true,

                'message' =>
                    'Profile updated successfully.'
            ];
        }
    );
}



public function getProfile()
{
    $authUser =
        request()->user();

    $sponsor =
        $this->sponsorRepository
            ->getSponsorByEmail(
                $authUser->email
            );

    if (!$sponsor) {

        throw new Exception(
            'Sponsor not found'
        );
    }

    $user =
        $this->sponsorRepository
            ->getUserById(
                $authUser->id
            );

    if (!$user) {

        throw new Exception(
            'User not found'
        );
    }

    return [

        'status' => true,

        'message' =>
            'Profile fetched successfully.',

        'data' => [

            'id' =>
                $user->id,

            'name' =>
                $user->name,

            'email' =>
                $user->email,

            'number' =>
                $user->number,

            'profile' =>
                $user->profile
                    ? asset(
                        'storage/' .
                        $user->profile
                    )
                    : null,

            'created_at' =>
                $user->created_at
        ]
    ];
}

}