<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'orderDetailID';

    protected $fillable = [
        'orderDetailID',
        'orderID',
        'productID',
        'productQty',
        'pricePerItem',
        'subTotal'
    ];

    public $timestamps = false;

    public $incrementing = false;
}
