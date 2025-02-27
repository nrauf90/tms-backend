<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PaypalCredential extends Model
{
    protected $table = 'paypal_credentials';
    use HasFactory;

    public function paypalUser(){
        return $this->belongsTo(User::class);
    }
}
