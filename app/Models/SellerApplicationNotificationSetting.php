<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerApplicationNotificationSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'enable_all',
        'new_order',
        'in_progress_order',
        'reject_order',
        'finish_order',
        'success_withdraw',
        'fail_withdraw',
        'promotion'
    ];
}
