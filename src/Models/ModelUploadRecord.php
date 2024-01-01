<?php

declare(strict_types=1);

namespace FromHome\ModelUpload\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ModelUploadRecord extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'meta' => 'array',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(ModelUploadFile::class, 'model_upload_file_id');
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function getPayloadData(?string $key = null, mixed $default = null): mixed
    {
        $meta = $this->getAttribute('payload') ?? [];

        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? $default;
    }

    public function getMetaData(?string $key = null, mixed $default = null): mixed
    {
        $meta = $this->getAttribute('meta') ?? [];

        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? $default;
    }
}
