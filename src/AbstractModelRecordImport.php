<?php

declare(strict_types=1);

namespace FromHome\ModelUpload;

use Webmozart\Assert\Assert;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Foundation\Bus\PendingDispatch;
use FromHome\ModelUpload\Models\ModelUploadFile;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use FromHome\ModelUpload\Jobs\ProcessModelRecordJob;

abstract class AbstractModelRecordImport implements ShouldQueue, SkipsUnknownSheets, WithBatchInserts, WithChunkReading, WithStartRow, WithEvents
{
    use Importable;

    protected ?ModelUploadFile $uploadFile = null;

    protected array $meta = [];

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

    public function onUnknownSheet($sheetName): void {}

    public function startRow(): int
    {
        return ModelUpload::importStartRow();
    }
}
