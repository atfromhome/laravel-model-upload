<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

final class StoreUserFileUploadView implements StoreUserFileUploadResponse
{
    public function toResponse($request): Response
    {
        return redirect()->back();
    }
}
