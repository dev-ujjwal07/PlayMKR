<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AttachmentService;
use App\Http\Requests\AttachmentRequest;
use App\Constants\AttachmentConstants;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(
        AttachmentService $attachmentService
    )
    {
        $this->attachmentService =
            $attachmentService;
    }

    public function store(
        AttachmentRequest $request
    )
    {
        $attachments =
            $this->attachmentService
                ->upload(
                    $request->validated()
                );

        return response()->json([

            'status' => true,

            'message' =>
                AttachmentConstants::ATTACHMENT_UPLOADED,

            'data' => $attachments

        ], 201);
    }
}