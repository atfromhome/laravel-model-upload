<?php

declare(strict_types=1);

namespace FromHome\ModelUpload;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use FromHome\ModelUpload\Models\ModelUploadFile;
use FromHome\ModelUpload\Actions\StoreModelUploadFile;
use FromHome\ModelUpload\Processor\RecordProcessorManager;

final class ModelUpload
{
    public static function registerRecordProcessors(array $processors): void
    {
        /** @var RecordProcessorManager $manager */
        $manager = app(RecordProcessorManager::class);

        $manager->registerRecordProcessors($processors);
    }

    public static function storeModelUploadFile(Request $request, array $meta = []): ModelUploadFile
    {
        /** @var StoreModelUploadFile $action */
        $action = app(StoreModelUploadFile::class);

        /** @var UploadedFile $file */
        $file = $request->file('file');

        return $action->handle(
            $request->user(), $file, $request->input('model_type'), $meta
        );
    }
}
