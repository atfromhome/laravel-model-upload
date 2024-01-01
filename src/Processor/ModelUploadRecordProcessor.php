<?php

namespace FromHome\ModelUpload\Processor;

use Illuminate\Database\Eloquent\Model;
use FromHome\ModelUpload\Models\ModelUploadRecord;

interface ModelUploadRecordProcessor
{
    public function process(ModelUploadRecord $record): Model;
}
