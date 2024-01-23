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
    private static string $importStartCell = 'A1';

    public static function importStartCell(): string
    {
        return self::$importStartCell;
    }

    public static function useImportStartCell(string $importStartCell): void
    {
        self::$importStartCell = $importStartCell;
    }

    /**
     * @throws Throwable
     */
    public static function useModelRecordImporter(string $importerClass): void
    {
        \throw_if(
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
            $request->user(), $file, $modelType ?? $request->input('model_type'), $meta
        );
    }
}
