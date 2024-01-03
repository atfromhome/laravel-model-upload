<?php

declare(strict_types=1);

namespace FromHome\ModelUpload;

use Spatie\LaravelPackageTools\Package;
use FromHome\ModelUpload\Actions\StoreModelUploadFile;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use FromHome\ModelUpload\Processor\RecordProcessorManager;
use FromHome\ModelUpload\Http\Responses\StoreUserFileUploadView;
use FromHome\ModelUpload\Http\Responses\CreateUserFileUploadView;
use FromHome\ModelUpload\Http\Responses\FetchUserFileUploadView;
use FromHome\ModelUpload\Http\Responses\ShowFileUploadRecordView;
use FromHome\ModelUpload\Http\Responses\FetchFileUploadRecordView;
use FromHome\ModelUpload\Http\Responses\StoreUserFileUploadResponse;
use FromHome\ModelUpload\Http\Responses\CreateUserFileUploadResponse;
use FromHome\ModelUpload\Http\Responses\FetchUserFileUploadResponse;
use FromHome\ModelUpload\Http\Responses\ShowFileUploadRecordResponse;
use FromHome\ModelUpload\Http\Responses\FetchFileUploadRecordResponse;

final class ModelUploadServiceProvider extends PackageServiceProvider
{
    public function registeringPackage(): void
    {
        $this->app->singleton(StoreModelUploadFile::class);
        $this->app->singleton(RecordProcessorManager::class);

        $this->app->bind(FetchUserFileUploadResponse::class, FetchUserFileUploadView::class);
        $this->app->bind(CreateUserFileUploadResponse::class, CreateUserFileUploadView::class);
        $this->app->bind(StoreUserFileUploadResponse::class, StoreUserFileUploadView::class);

        $this->app->bind(FetchFileUploadRecordResponse::class, FetchFileUploadRecordView::class);
        $this->app->bind(ShowFileUploadRecordResponse::class, ShowFileUploadRecordView::class);
    }

    public function configurePackage(Package $package): void
    {
        $package->name('laravel-model-upload')
            ->hasMigration('create_model_upload_table');
    }
}
