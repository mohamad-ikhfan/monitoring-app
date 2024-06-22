<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sizerun extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function poItems(): HasMany
    {
        return $this->hasMany(PoItem::class);
    }

    public function ProductionOutsole(): HasMany
    {
        return $this->hasMany(ProductionOutsole::class);
    }
}