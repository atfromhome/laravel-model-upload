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
        $processorClass = $this->processors[$modelType] ?? null;

        if ($processorClass === null) {
            return null;
        }

        $processor = app($processorClass);

        Assert::isInstanceOf($processor, ModelUploadRecordProcessor::class);

        return $processor;
    }
}
