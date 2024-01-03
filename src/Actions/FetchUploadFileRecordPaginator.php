<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Actions;

use FromHome\ModelUpload\Models\ModelUploadFile;
use Illuminate\Contracts\Pagination\CursorPaginator;

final class FetchUploadFileRecordPaginator
{
    public function handle(ModelUploadFile $uploadFile): CursorPaginator
    {
        return $uploadFile->records()
            ->with('model')
            ->cursorPaginate()
            ->withQueryString();
    }
}
