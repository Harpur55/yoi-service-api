<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create($validated_request)
 * @method static find($id)
 */
class SellerBankAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'account_name',
        'account_number',
        'account_provider',
        'provider_name',
        'is_active'
    ];
}
