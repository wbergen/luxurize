<?php

namespace App\Models;

use App\Models\Payments\PaymentRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'obligation_id',
        'payment_record_id',
        'promotion_id',
        'price',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obligation_id' => 'integer',
        'payment_record_id' => 'integer',
        'promotion_id' => 'integer',
        'price' => 'decimal:2',
        'user_id' => 'integer',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Products\Product::class)->withPivot('quantity');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function obligation(): BelongsTo
    {
        return $this->belongsTo(Obligation::class);
    }

    public function paymentRecord(): BelongsTo
    {
        return $this->belongsTo(PaymentRecord::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Products\Promotion::class);
    }
}
