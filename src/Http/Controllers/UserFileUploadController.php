<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Contracts\Support\Responsable;
use FromHome\ModelUpload\Pipelines\StoreUploadFile;
use FromHome\ModelUpload\Http\Responses\FetchUserFileUploadResponse;
use FromHome\ModelUpload\Http\Responses\StoreUserFileUploadResponse;
use FromHome\ModelUpload\Http\Responses\CreateUserFileUploadResponse;

final class UserFileUploadController
{
    public function index(): Responsable
    {
        return app(FetchUserFileUploadResponse::class);
    }

    public function create(): Responsable
    {
        return app(CreateUserFileUploadResponse::class);
    }

    public function store(Request $request): Responsable
    {
        Pipeline::send($request)->through([
            StoreUploadFile::class,
        ]);

        return app(StoreUserFileUploadResponse::class);
    }
}
