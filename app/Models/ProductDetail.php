<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($id)
 * @method static create($validated_request)
 */
class ProductDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'discount',
        'keyword',
        'description',
        'sku',
        'stock_status',
        'selected_service',
        'selected_size',
        'selected_color',
        'weight',
        'long',
        'wide',
        'tall',
        'visibility',
        'additional_note',
        'show_additional_note',
        'image'
    ];
}
