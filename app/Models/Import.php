<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany as HasManyAlias;

class Import extends Model
{
    public const IMPORT_STATUS_PENDING = 'pending';
    public const IMPORT_STATUS_IN_PROGRESS = 'in_progress';
    public  const  IMPORT_STATUS_COMPLETED = 'completed';

    public const IMPORT_PATH = 'imports';
    public const IMPORT_RESULT_PATH = 'import-results';

    protected $fillable = [
        'original_file_name',
        'file_name',
        'status',
        'started_at',
        'finished_at',
        'total_rows',
    ];

    public function rows() : HasManyAlias
    {
        return $this->hasMany(Row::class, 'import_id', 'id');
    }
}
