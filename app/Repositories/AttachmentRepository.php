<?php

namespace App\Repositories;

use App\Models\Attachment;
use App\Interfaces\AttachmentRepositoryInterface;

class AttachmentRepository
implements AttachmentRepositoryInterface
{
    public function createAttachment(
        array $data
    )
    {
        return Attachment::create(
            $data
        );
    }
}