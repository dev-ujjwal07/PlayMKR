<?php

namespace App\Services;

use App\Interfaces\AttachmentRepositoryInterface;

class AttachmentService
{
    protected $attachmentRepository;

    public function __construct(
        AttachmentRepositoryInterface $attachmentRepository
    )
    {
        $this->attachmentRepository =
            $attachmentRepository;
    }

    public function upload(
        array $data
    )
    {
        $attachments = [];

        foreach (
            $data['attachments']
            as $file
        ) {

            $path = $file->store(
                'attachments',
                'public'
            );

            $attachments[] =
                $this->attachmentRepository
                    ->createAttachment([

                        'deliverable_id' =>
                            $data['deliverable_id'],

                        'attachment' =>
                            $path
                    ]);
        }

        return $attachments;
    }
}