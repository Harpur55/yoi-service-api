<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreTermAndCondition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_id',
        'show_term_and_condition',
        'term_description',
        'show_time_operational',
        'day_operation',
        'opening_time_operational',
        'opening_time_operational_description',
        'closing_time_operational',
        'closing_time_operational_description',
    ];
}
