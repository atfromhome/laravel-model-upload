<?php

declare(strict_types=1);

namespace FromHome\ModelUpload;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use FromHome\ModelUpload\Actions\StoreModelUploadFile;
use FromHome\ModelUpload\Processor\RecordProcessorManager;

final class ModelUploadServiceProvider extends PackageServiceProvider
{
    public function registeringPackage(): void
    {
        $this->app->singleton(StoreModelUploadFile::class);
        $this->app->singleton(RecordProcessorManager::class);
    }

    public function configurePackage(Package $package): void
    {
        $package->name('laravel-model-upload')
            ->hasMigration('create_model_upload_table');
    }
}
