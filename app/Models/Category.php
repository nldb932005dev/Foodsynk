<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'status'];
        public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class);
    }
}
