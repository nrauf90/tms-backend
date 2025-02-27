<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyDetail;

class Currency extends Model
{
    protected $table = 'currencies';
    use HasFactory;

    public function company(){
        return $this->hasMany(CompanyDetail::class, 'currency_id');
    }
}
