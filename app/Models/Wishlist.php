<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'customer_id'
    ];

    /**
     * Relationship with Product Model
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relationship with WishlistDetail Model
     */
    public function detail()
    {
        return $this->hasOne(WishlistDetail::class);
    }

    public function delete()
    {
        $this->detail()->delete();
        
        return parent::delete();
    }
}
