<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Enums;

enum UploadFileState: string
{
    case upload = 'upload';

    case process = 'process';

    case partialSuccess = 'partial success';

    case error = 'error';

    case done = 'done';
}
