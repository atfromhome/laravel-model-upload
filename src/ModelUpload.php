<?php

declare(strict_types=1);

namespace FromHome\ModelUpload;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use FromHome\ModelUpload\Models\ModelUploadFile;
use FromHome\ModelUpload\Actions\StoreModelUploadFile;
use FromHome\ModelUpload\Processor\RecordProcessorManager;

final class ModelUpload
{
    private static int $importStartRow = 1;

    public static function importStartRow(): int
    {
        return self::$importStartRow;
    }

    public static function useImportStartRow(int $importStartRow): void
    {
        self::$importStartRow = $importStartRow;
    }

    /**
     * @throws Throwable
     */
    public static function useModelRecordImporter(string $importerClass): void
    {
        \throw_unless(
            \class_exists($importerClass),
            new \InvalidArgumentException('Invalid concrete class')
        );

        app()->bind(AbstractModelRecordImport::class, $importerClass);
    }

    public static function registerRecordProcessors(array $processors): void
    {
        /** @var RecordProcessorManager $manager */
        $manager = app(RecordProcessorManager::class);

        $manager->registerRecordProcessors($processors);
    }

    public static function storeModelUploadFile(Request $request, array $meta = [], ?string $modelType = null): ModelUploadFile
    {
        /** @var StoreModelUploadFile $action */
        $action = app(StoreModelUploadFile::class);

        /** @var UploadedFile $file */
        $file = $request->file('file');

        return $action->handle(
            $request->user(),
            $file,
            $modelType ?? $request->input('model_type'),
            $meta
        );
    }
}
