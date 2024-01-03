<?php

namespace FromHome\ModelUpload\Http\Responses;

use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use FromHome\ModelUpload\Models\ModelUploadRecord;

final class ShowFileUploadRecordView implements ShowFileUploadRecordResponse
{
    public function toResponse($request): Response
    {
        $record = $request->route('record');

        /** @var ModelUploadRecord $modelUploadRecord */
        $modelUploadRecord = $record instanceof ModelUploadRecord ?
            $record->loadMissing(['model', 'file']) :
            ModelUploadRecord::query()->with(['model', 'file'])->findOrFail($record);

        return Inertia::render('model-upload/record', [
            'record' => $modelUploadRecord,
        ])->toResponse($request);
    }
}
