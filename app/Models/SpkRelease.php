<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpkRelease extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function spkReleasePoItems(): HasMany
    {
        return $this->hasMany(SpkReleasePoItem::class);
    }

    public function productionOutsoles(): HasMany
    {
        return $this->hasMany(ProductionOutsole::class);
    }

    public function productionUppers(): HasMany
    {
        return $this->hasMany(ProductionUpper::class);
    }

    public function productionAssemblies(): HasMany
    {
        return $this->hasMany(ProductionAssembly::class);
    }
}