<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProviderOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id',
        'provider_status',
        'provider_cut',
        'provider_gross',
        'provider_net',
        'provider_payment_id',
        'payer_id',
        'payer_email',
        'payer_name',
        'payer_last_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'provider_cut' => 'decimal:2',
        'provider_gross' => 'decimal:2',
        'provider_net' => 'decimal:2',
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
