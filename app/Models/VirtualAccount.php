<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    use HasFactory;

    protected $table ="virtual_accounts";

   protected $primaryKey = "virtualAccount";

    protected $fillable = [
        'customerID',
        'methodID',
        'virtualAccount',
        'bill'
    ];

    public $timestamps = false;
}