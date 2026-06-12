<?php

namespace App\Interfaces;

interface AttachmentRepositoryInterface
{
    public function createAttachment(
        array $data
    );
}