<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StripeCredential extends Model
{
    protected $table = 'stripe_credentials';
    use HasFactory;

    public function stripeUser(){
        return $this->belongsTo(User::class);
    }

}
