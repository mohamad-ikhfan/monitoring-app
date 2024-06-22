<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PoItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sizerun(): BelongsTo
    {
        return $this->belongsTo(Sizerun::class);
    }

    public function spkReleasePoItem(): HasOne
    {
        return $this->hasOne(SpkReleasePoItem::class);
    }
}
