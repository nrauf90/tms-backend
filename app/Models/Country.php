<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyDetail;


class Country extends Model
{
    protected $table = 'countries';
    use HasFactory;

    public function companyCountry(){
        return $this->hasMany(CompanyDetail::class, 'country_id');
    }

}
