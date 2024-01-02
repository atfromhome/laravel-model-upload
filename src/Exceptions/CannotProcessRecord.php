<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Exceptions;

final class CannotProcessRecord extends ModelRecordProcessorException
{
    public static function make(string $reason): ModelRecordProcessorException
    {
        return new self(
            'Cannot process record : '.$reason,
        );
    }
}
