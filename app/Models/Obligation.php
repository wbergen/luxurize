<?php

namespace App\Models;

use App\Models\Products\Obligables\Obligable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Obligation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'obligation_status_id',
        'user_id',
        'order_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obligation_status_id' => 'integer',
        'user_id' => 'integer',
        'order_id' => 'integer',
    ];

    public function obligationStatus(): BelongsTo
    {
        return $this->belongsTo(ObligationStatus::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function obligables()
    {
        return $this->hasMany(Obligable::class);
    }
}
