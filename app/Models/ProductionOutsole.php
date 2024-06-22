<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionOutsole extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function spkRelease(): BelongsTo
    {
        return $this->belongsTo(SpkRelease::class);
    }

    public function outsoleSizeruns(): HasMany
    {
        return $this->hasMany(OutsoleProductionSize::class);
    }
}
