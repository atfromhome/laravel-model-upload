<?php

declare(strict_types=1);

namespace FromHome\ModelUpload;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ModelUploadServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-model-upload')
            ->hasConfigFile()
            ->hasMigration('create_model_upload_table');
    }
}
