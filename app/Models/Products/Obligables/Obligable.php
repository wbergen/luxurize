<?php

namespace App\Models\Products\Obligables;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class Obligable extends Model
{
    public function obligation(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Obligation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
