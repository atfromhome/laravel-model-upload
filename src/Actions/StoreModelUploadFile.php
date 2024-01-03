<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Actions;

use Illuminate\Http\UploadedFile;
use FromHome\ModelUpload\ModelRecordImport;
use Illuminate\Contracts\Auth\Authenticatable;
use FromHome\ModelUpload\Enums\UploadFileState;
use FromHome\ModelUpload\Models\ModelUploadFile;
use Illuminate\Database\Eloquent\Relations\Relation;

final class StoreModelUploadFile
{
    public function handle(Authenticatable $user, UploadedFile $uploadedFile, string $modelType, array $meta): ModelUploadFile
    {
        if (! \array_key_exists($modelType, Relation::morphMap())) {
            throw new \InvalidArgumentException(
                \sprintf('Invalid `modelType`, valid value is [%s]', \implode(',', Relation::morphMap()))
            );
        }

        /** @var ModelUploadFile $file */
        $file = ModelUploadFile::query()->create([
            'user_id' => $user->getAuthIdentifier(),
            'model_type' => $modelType,
            'file_name' => $uploadedFile->getClientOriginalName(),
            'storage_disk' => \config('filesystems.default'),
            'file_path' => $uploadedFile->store(),
            'state' => UploadFileState::upload,
        ]);

        ModelRecordImport::new()->forFile($file)->withMeta($meta)->process();

        return $file;
    }
}
