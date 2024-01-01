<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Processor;

use Webmozart\Assert\Assert;

final class RecordProcessorManager
{
    private array $processors = [];

    public function registerRecordProcessors(array $processors): void
    {
        $this->processors = \array_merge($this->processors, $processors);
    }

    public function getRecordProcessor(string $modelType): ?ModelUploadRecordProcessor
    {
        $actionClass = $this->actions[$modelType] ?? null;

        if ($actionClass === null) {
            return null;
        }

        $action = app($actionClass);

        Assert::isInstanceOf($action, ModelUploadRecordProcessor::class);

        return $action;
    }
}
