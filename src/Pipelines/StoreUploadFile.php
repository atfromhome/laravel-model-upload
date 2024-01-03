<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Pipelines;

use Illuminate\Http\Request;
use FromHome\ModelUpload\ModelUpload;

final class StoreUploadFile
{
    public function handle(Request $request, callable $next): mixed
    {
        $model = ModelUpload::storeModelUploadFile(
            $request,
            $request->except(['file', 'model_type'])
        );

        return $next($model);
    }
}
