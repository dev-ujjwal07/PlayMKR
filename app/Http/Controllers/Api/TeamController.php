<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\TeamService;
use App\Constants\TeamConstants;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateInternalTeamProfileRequest;

class TeamController extends Controller
{
    protected $teamService;

    public function __construct(
        TeamService $teamService
    )
    {
        $this->teamService =
            $teamService;
    }

    public function store(
        CreateTeamRequest $request
    )
    {
        try {

            $team =
                $this->teamService
                    ->create(
                        $request->validated()
                    );

            return response()->json([

                'status' => true,

                'message' =>
                    TeamConstants
                        ::TEAM_CREATED,

                'data' => $team

            ], 201);

        } catch (Exception $e) {

            return response()->json([

                'status' => false,

                'message' =>
                    $e->getMessage()

            ], 422);
        }
    }



    public function update(
    UpdateTeamRequest $request,
    int $id
)
{
    try {

        $team =
            $this->teamService
                ->update(
                    $id,
                    $request->validated()
                );

        return response()->json([

            'status' => true,

            'message' =>
                TeamConstants
                    ::TEAM_UPDATED,

            'data' => $team

        ], 200);

    } catch (Exception $e) {

        $statusCode = 422;

        if (
            $e->getMessage() ===
            TeamConstants::TEAM_NOT_FOUND
        ) {
            $statusCode = 404;
        }

        return response()->json([

            'status' => false,

            'message' =>
                $e->getMessage()

        ], $statusCode);
    }

    
}





public function destroy(
    int $id
)
{
    try {

        $this->teamService
            ->delete($id);

        return response()->json([

            'status' => true,

            'message' =>
                TeamConstants
                    ::TEAM_DELETED

        ], 200);

    } catch (Exception $e) {

        $statusCode = 422;

        if (
            $e->getMessage() ===
            TeamConstants::TEAM_NOT_FOUND
        ) {
            $statusCode = 404;
        }

        return response()->json([

            'status' => false,

            'message' =>
                $e->getMessage()

        ], $statusCode);
    }
}



public function index(
    Request $request
)
{
    $teams =
        $this->teamService
            ->getTeams([

                'search' =>
                    $request->search,

                'per_page' =>
                    $request->per_page
            ]);

    return response()->json([

        'status' => true,

        'message' =>
            'Teams fetched successfully',

        'data' =>
            $teams['data'],

        'current_page' =>
            $teams['current_page'],

        'last_page' =>
            $teams['last_page'],

        'per_page' =>
            $teams['per_page'],

        'total' =>
            $teams['total']
    ]);
}



public function updateInternalTeamProfile(
    UpdateInternalTeamProfileRequest $request
)
{
    $response =
        $this->teamService
            ->updateInternalTeamProfile(
                $request->validated()
            );

    return response()->json(

        $response,

        200
    );
}



public function getInternalTeamProfile()
{
    $profile =
        $this->teamService
            ->getInternalTeamProfile();

    return response()->json(
        $profile
    );
}
}