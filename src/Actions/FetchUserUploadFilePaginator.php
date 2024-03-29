<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Actions;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use FromHome\ModelUpload\Models\ModelUploadFile;
use Illuminate\Contracts\Pagination\CursorPaginator;

final class FetchUserUploadFilePaginator
{
    public function handle(Request $request, ?string $modelType = null): CursorPaginator
    {
        $keyword = $request->string('keyword');

        return ModelUploadFile::query()
            ->withCount('records')
            ->when($keyword, function (Builder $builder) use ($keyword): void {
                $value = \mb_strtolower((string) $keyword, 'UTF8');

                $builder->orWhereRaw('LOWER(file_name) LIKE ?', ["%{$value}%"]);
            })
            ->when($modelType, fn (Builder $builder) => $builder->where('model_type', $modelType))
            ->where('user_id', $request->user()?->getKey())
            ->latest()
            ->cursorPaginate()
            ->withQueryString();
    }
}
