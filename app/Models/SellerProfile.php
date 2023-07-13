<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'full_name',
        'full_address',
        'photo_path',
        'latitude',
        'longitude',
        'phone',
        'slogan',
        'description',
        'store_name'
    ];
}
