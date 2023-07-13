<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Conner\Likeable\Likeable;
use Illuminate\Support\Facades\Auth;

/**
 * @method static find($id)
 * @method static create($validated_request)
 */
class Product extends Model
{
    use HasFactory, Likeable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_category_id',
        'store_id'
    ];

    /**
     * Relationship with ProductCategory Model
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Relationship with ProductDetail Model
     */
    public function detail(): HasOne
    {
        return $this->hasOne(ProductDetail::class);
    }

    /**
     * Relationship with ProductRate Model
     */
    public function rate(): HasOne
    {
        return $this->hasOne(ProductRate::class);
    }

    /**
     * Relationship with Store Model
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function review()
    {
        return $this->belongsTo(ProductReview::class);
    }
}
