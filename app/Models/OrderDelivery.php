<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    use HasFactory;

    protected $primaryKey = 'deliveryID';

    protected $fillable = [
        'deliveryID',
        'orderID',
        'deliveryServiceID',
        'distance',
        'deliveryWeight',
        'pricePerKm',
        'addWeightPrice',
        'deliveryTotalPrice',
        'deliveryStatus'
    ];

    public $timestamps = false;
}
