<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflinePaymentMethod extends Model
{
    protected $table = 'offline_payment_methods';
    use HasFactory;
}
