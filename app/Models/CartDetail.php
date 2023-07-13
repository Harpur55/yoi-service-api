<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cart_id',
        'store_name',
        'amount',
        'price',
        'discount',
        'selected_size',
        'selected_color'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
