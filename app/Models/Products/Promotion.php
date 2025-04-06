<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'discount_percent',
        'discount_value',
        'obligation_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'discount_percent' => 'decimal:4',
        'discount_value' => 'decimal:2',
        'obligation_id' => 'integer',
    ];

    public function obligation(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Obligation::class);
    }
}
