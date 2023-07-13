<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_rate_id',
        'customer_id',
        'review',
        'rate'
    ];

    /**
     * Relationship with ProductRate Model
     */
    public function productRate(): BelongsTo
    {
        return $this->belongsTo(ProductRate::class, 'product_rate_id');
    }

    /**
     * Relationship with Customer Model
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
