<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Auth\Authenticatable;
use FromHome\ModelUpload\Enums\UploadFileState;
use FromHome\ModelUpload\Models\ModelUploadFile;
use FromHome\ModelUpload\AbstractModelRecordImport;

final class StoreModelUploadFile
{
    public function __construct(
        private readonly AbstractModelRecordImport $modelRecordImport) {}

    public function handle(Authenticatable $user, UploadedFile $uploadedFile, string $modelType, array $meta): ModelUploadFile
    {
        /** @var ModelUploadFile $file */
        $file = ModelUploadFile::query()->create([
            'user_id' => $user->getAuthIdentifier(),
            'model_type' => $modelType,
            'file_name' => $uploadedFile->getClientOriginalName(),
            'storage_disk' => \config('filesystems.default'),
            'file_path' => $uploadedFile->store('model-upload'),
            'state' => UploadFileState::upload,
        ]);

        $this->modelRecordImport->forFile($file)->withMeta($meta)->process();

        return $file;
    }
}
