<?php

namespace FromHome\ModelUpload\Models;

use Illuminate\Database\Eloquent\Model;
use FromHome\ModelUpload\Enums\UploadFileState;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ModelUploadFile extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'state' => UploadFileState::class,
    ];

    public function records(): HasMany
    {
        return $this->hasMany(ModelUploadRecord::class);
    }

    public function isError(): bool
    {
        if ($this->isSuccess()) {
            return false;
        }

        $recordCount = $this->records()->count('id');

        $recordErrorCount = $this->records()->whereNotNull('error_message')->count('id');

        return $recordCount === $recordErrorCount;
    }

    public function isSuccess(): bool
    {
        return $this->records()->whereNotNull('error_message')->exists();
    }
}
