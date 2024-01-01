<?php

declare(strict_types=1);

namespace FromHome\ModelUpload;

use Webmozart\Assert\Assert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\PendingDispatch;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use FromHome\ModelUpload\Models\ModelUploadFile;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use FromHome\ModelUpload\Models\ModelUploadRecord;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use FromHome\ModelUpload\Jobs\ProcessModelRecordJob;

final class ModelRecordImport implements ShouldQueue, SkipsUnknownSheets, ToModel, WithBatchInserts, WithChunkReading, WithEvents, WithHeadingRow, WithMultipleSheets
{
    use Importable;

    private ?ModelUploadFile $uploadFile = null;

    private array $meta = [];

    public static function new(): self
    {
        return new self();
    }

    public function forFile(ModelUploadFile $uploadFile): self
    {
        $this->uploadFile = $uploadFile;

        return $this;
    }

    public function withMeta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function process(): PendingDispatch
    {
        Assert::notNull($this->uploadFile);

        return $this->queue(
            $this->uploadFile->getAttribute('file_path'),
            $this->uploadFile->getAttribute('storage_disk'),
        );
    }

    public function model(array $row): ModelUploadRecord
    {
        Assert::notNull($this->uploadFile);

        return new ModelUploadRecord([
            'model_upload_file_id' => $this->uploadFile->getKey(),
            'payload' => $row,
            'meta' => $this->meta,
        ]);
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (): void {
                Assert::notNull($this->uploadFile);

                dispatch(new ProcessModelRecordJob($this->uploadFile));
            },
        ];
    }

    public function sheets(): array
    {
        return [
            'DATA' => $this,
        ];
    }

    public function onUnknownSheet($sheetName): void
    {
    }
}
