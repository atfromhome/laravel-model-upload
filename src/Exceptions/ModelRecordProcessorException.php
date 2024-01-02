<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Exceptions;

use Exception;

abstract class ModelRecordProcessorException extends Exception
{
    abstract public static function make(string $reason): self;
}
