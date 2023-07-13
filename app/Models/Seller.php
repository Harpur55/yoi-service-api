<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create($validated_request)
 * @method static find(mixed $seller_id)
 */
class Seller extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class, 'seller_id');
    }

    public function code(): HasOne
    {
        return $this->hasOne(SellerAuthenticationCode::class, 'seller_id');
    }

    public function emailNotif(): HasOne
    {
        return $this->hasOne(SellerEmailNotificationSetting::class, 'seller_id');
    }

    public function appNotif(): HasOne
    {
        return $this->hasOne(SellerApplicationNotificationSetting::class, 'seller_id');
    }

    public function profile()
    {
        return $this->hasOne(SellerProfile::class);
    }
}
