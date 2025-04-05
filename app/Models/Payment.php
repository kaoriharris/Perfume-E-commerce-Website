<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table ="payments";

    protected $primaryKey = 'paymentID';

    protected $fillable = [
        'orderID',
        'methodID',
        'paymentAmount'
    ];

    public $timestamps = false;
}
