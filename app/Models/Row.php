<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Row extends Model
{
    protected $fillable = [
        'import_id',
        'excel_id',
        'name',
        'date',
        'line_number',
    ];

    public function import() : BelongsTo
    {
        return $this->belongsTo(Import::class, 'import_id', 'id');
    }
}
