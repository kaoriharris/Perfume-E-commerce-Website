<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table ="orders";

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
