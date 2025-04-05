<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactionHistory extends Model
{
    use HasFactory;

    protected $table ="transactionHistory";

    protected $primaryKey = 'orderID';

    protected $fillable = [
        'orderID',
        'customerID',
        'orderTime',
        'totalPrice',
        'pointsUsed',
        'orderStatus'
    ];

    public $timestamps = false;
}
