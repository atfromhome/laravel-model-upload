<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Http\Controllers;

use Illuminate\Contracts\Support\Responsable;
use FromHome\ModelUpload\Http\Responses\ShowFileUploadRecordResponse;
use FromHome\ModelUpload\Http\Responses\FetchFileUploadRecordResponse;

final class FileUploadRecordController
{
    public function index(): Responsable
    {
        return app(FetchFileUploadRecordResponse::class);
    }

    public function show(): Responsable
    {
        return app(ShowFileUploadRecordResponse::class);
    }
}
