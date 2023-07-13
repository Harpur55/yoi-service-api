<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $input_order_payment)
 */
class OrderPayment extends Model
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
        'payment_method',
        'payment_instruction_api',
        'payment_created_at',
        'payment_expired_at',
        'paid_at',
        'payment_body_detail',
        'payment_header_detail',
        'payment_type'
    ];
}
