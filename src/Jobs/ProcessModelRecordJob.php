<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use FromHome\ModelUpload\Enums\UploadFileState;
use FromHome\ModelUpload\Models\ModelUploadFile;
use FromHome\ModelUpload\Models\ModelUploadRecord;
use FromHome\ModelUpload\Processor\RecordProcessorManager;
use FromHome\ModelUpload\Events\ModelUploadFileWasProcessed;

final class ProcessModelRecordJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly ModelUploadFile $modelUploadFile
    ) {}

    public function handle(RecordProcessorManager $manager): void
    {
        $this->modelUploadFile->update([
            'state' => UploadFileState::process,
        ]);

        $action = $manager->getRecordProcessor(
            $this->modelUploadFile->getAttribute('model_type')
        );

        if ($action === null) {
            $this->modelUploadFile->update([
                'state' => UploadFileState::error,
                'error_message' => \sprintf(
                    'Invalid `null` action for %s type', $this->modelUploadFile->getAttribute('model_type')
                ),
            ]);

            event(new ModelUploadFileWasProcessed($this->modelUploadFile));

            return;
        }

        $errorCount = 0;
        $recordCount = $this->modelUploadFile->records()->count('id');

        $this->modelUploadFile->records()->each(function (ModelUploadRecord $modelUpload) use ($action, &$errorCount): void {
            try {
                $model = $action->process($modelUpload);

                $modelUpload->update([
                    'model_id' => $model->getKey(),
                    'model_type' => $this->modelUploadFile->getAttribute('model_type'),
                ]);
            } catch (Exception $exception) {
                $modelUpload->update([
                    'error_message' => $exception->getMessage(),
                    'exception' => $exception->getTraceAsString(),
                ]);

                $errorCount++;
            }
        });

        $errorState = $errorCount !== $recordCount ? UploadFileState::partialSuccess : UploadFileState::error;

        $this->modelUploadFile->update([
            'state' => $errorCount === 0 ? UploadFileState::done : $errorState,
        ]);

        event(new ModelUploadFileWasProcessed($this->modelUploadFile));
    }
}
