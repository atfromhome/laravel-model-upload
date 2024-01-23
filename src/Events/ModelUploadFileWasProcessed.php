<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Events;

use FromHome\ModelUpload\Models\ModelUploadFile;

final class ModelUploadFileWasProcessed
{
    public function __construct(
        public readonly ModelUploadFile $modelUploadFile
    ) {
    }
}
