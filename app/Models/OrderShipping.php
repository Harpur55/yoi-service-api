<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'status',
        'etd',
        'service_code',
        'service_name',
        'courier_code',
        'courier_name',
        'price',
        'order_shipping_code',
        'origin_code',
        'destination_code'
    ];
}
