<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static find($id)
 * @method static create($validated_request)
 */
class Customer extends Model
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

    /**
     * Relationship with User Model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function emailNotif(): HasOne
    {
        return $this->hasOne(CustomerEmailNotificationSetting::class, 'customer_id');
    }

    public function appNotif(): HasOne
    {
        return $this->hasOne(CustomerApplicationNotificationSetting::class, 'customer_id');
    }

    public function address()
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
