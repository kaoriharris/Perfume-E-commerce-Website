<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table ="payment_methods";

    protected $primaryKey = 'methodID';

    protected $fillable = [
        'bankName',
        'bankCode',
        'logo'
    ];

    public $timestamps = false;
}
