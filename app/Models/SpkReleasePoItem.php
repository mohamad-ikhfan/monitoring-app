<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SpkReleasePoItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function spkRelease(): BelongsTo
    {
        return $this->belongsTo(SpkRelease::class);
    }

    public function poItem(): HasOne
    {
        return $this->hasOne(PoItem::class, 'id', 'po_item_id');
    }
}