<?php

namespace App\Models\Products\Obligables;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Obligable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dt',
        'conference_link',
        'address_id',
        'obligation_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'dt' => 'datetime',
        'address_id' => 'integer',
        'obligation_id' => 'integer',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
