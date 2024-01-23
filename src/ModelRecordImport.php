<?php

declare(strict_types=1);

namespace FromHome\ModelUpload;

use Illuminate\Support\Str;
use Webmozart\Assert\Assert;
use Maatwebsite\Excel\Concerns\ToModel;
use FromHome\ModelUpload\Models\ModelUploadRecord;

final class ModelRecordImport extends AbstractModelRecordImport implements ToModel
{
    public function model(array $row): ModelUploadRecord
    {
        Assert::notNull($this->uploadFile);

        return new ModelUploadRecord([
            'id' => \strtolower((string) Str::ulid()),
            'model_upload_file_id' => $this->uploadFile->getKey(),
            'payload' => $row,
            'meta' => $this->meta,
        ]);
    }
}
