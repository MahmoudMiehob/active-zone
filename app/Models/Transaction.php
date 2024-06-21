<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tran_ref',
        'tran_type',
        'cart_amount',
        'tran_total',
        'reservation_id',
        'user_id',
    ];
}
