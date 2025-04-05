<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = ['customerID', 'productID', 'qty'];

    // protected $primaryKey = ['customerID', 'productID'];

    // Disable incrementing for composite primary key
    // public $incrementing = false;

    public $timestamps = false;
}
