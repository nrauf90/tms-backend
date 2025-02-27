<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\Company;
use App\Models\Language;
use App\Models\Timezone;
use App\Models\Currency;


class CompanyDetail extends Model
{
    protected $table = 'company_details';
    use HasFactory;

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function language(){
        return $this->belongsTo(Language::class);
    }

    public function timezone(){
        return $this->belongsTo(Timezone::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }



}
