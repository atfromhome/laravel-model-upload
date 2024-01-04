<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Http\Responses;

use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

final class CreateUserFileUploadView implements CreateUserFileUploadResponse
{
    public function toResponse($request): Response
    {
        return Inertia::render('model-upload/create')->toResponse($request);
    }
}
