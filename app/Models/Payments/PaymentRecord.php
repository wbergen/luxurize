<?php

namespace App\Models\Payments;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PaymentRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'obligation_id',
        'provider_record_type',
        'provider_record_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obligation_id' => 'integer',
        'provider_record_id' => 'string',
        'provider_record_type' => 'string',
    ];

    public function obligation(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Obligation::class);
    }

    public function providerRecord(): MorphTo
    {
        return $this->morphTo();
    }

    public static function findByProvider(string $provider, mixed $providerId): ?static
    {
        $providerRecordId = $provider::where('provider_id', $providerId)->value('id');
        return static::where('provider_record_type', $provider)->where('provider_record_id', $providerRecordId)->first();
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

}
