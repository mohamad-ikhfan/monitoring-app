<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpperProductionSize extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sizerun(): BelongsTo
    {
        return $this->belongsTo(Sizerun::class);
    }
}