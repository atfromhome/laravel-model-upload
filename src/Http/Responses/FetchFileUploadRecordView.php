<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Http\Responses;

use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use FromHome\ModelUpload\Models\ModelUploadFile;
use FromHome\ModelUpload\Actions\FetchUploadFileRecordPaginator;

final class FetchFileUploadRecordView implements FetchFileUploadRecordResponse
{
    public function __construct(
        private readonly FetchUploadFileRecordPaginator $recordPaginator,
    ) {
    }

    public function toResponse($request): Response
    {
        $model = $request->route('model');

        /** @var ModelUploadFile $modelUploadFile */
        $modelUploadFile = $model instanceof ModelUploadFile ?
            $model :
            ModelUploadFile::query()->findOrFail($model);

        return Inertia::render('model-upload/show', [
            'model' => $modelUploadFile,
            'records' => $this->recordPaginator->handle($modelUploadFile),
        ])->toResponse($request);
    }
}
