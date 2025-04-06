<?php

namespace App\Models\Products\Obligables;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shippable extends Obligable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'obligation_id',
        'shipping_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obligation_id' => 'integer',
        'shipping_id' => 'integer',
    ];

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Shipping::class);
    }
}
