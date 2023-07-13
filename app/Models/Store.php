<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($id)
 * @method static create($validated_request)
 */
class Store extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function courierShipping()
    {
        return $this->hasMany(StoreCourierShippingAddress::class, 'store_id');
    }

    public function tac()
    {
        return $this->hasMany(StoreTermAndCondition::class, 'store_id');
    }
}
