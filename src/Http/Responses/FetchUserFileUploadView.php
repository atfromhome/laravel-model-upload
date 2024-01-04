<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Http\Responses;

use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use FromHome\ModelUpload\Actions\FetchUserUploadFilePaginator;

final class FetchUserFileUploadView implements FetchUserFileUploadResponse
{
    public function __construct(
        private readonly FetchUserUploadFilePaginator $filePaginator
    ) {
    }

    public function toResponse($request): Response
    {
        return Inertia::render('model-upload/list', [
            'files' => $this->filePaginator->handle($request),
        ])->toResponse($request);
    }
}
