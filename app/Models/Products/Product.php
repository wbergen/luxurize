<?php

namespace App\Models\Products;

use App\Models\Obligation;
use App\Models\Products\Obligables\Meeting;
use App\Models\Products\Obligables\Obligable;
use App\Models\Products\Obligables\Shippable;
use App\Models\Products\Obligables\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'for_sale',
        'image',
        'product_type_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'price' => 'decimal:2',
        'product_type_id' => 'integer',
    ];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function shoppingCarts(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\ShoppingCart::class);
    }

    public function obligations(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Obligation::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Order::class)->withPivot('quantity');
    }

    public function makeAndPersistObligable(Obligation $obligation, array $data = []): Obligable
    {
        $productClass = $this->productType()->first()->class;
        $o = app()->make($productClass);
        $o->obligation()->associate($obligation);
        $o->product()->associate($this);
        switch ($productClass) {
            case Shippable::class:
                $o->shipping()->associate($data['shipping']);
                break;
            case Subscription::class:
                // TODO Handle rest of Sub logic
                $o->active = true;
                break;
            case Meeting::class:
                //
                break;
        }
        $o->save();
        return $o;
    }

    public function getUri(): string
    {
        return sprintf('/products/%d', $this->id);
    }

    public function imageUri(): string
    {
        if (str_starts_with($this->image, '/')) {
            return sprintf('%s/%s', config('app.image_url'), $this->image);
        }
        return $this->image;
    }
}
