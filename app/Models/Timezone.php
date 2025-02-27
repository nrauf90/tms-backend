<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyDetail;

class Timezone extends Model
{
    protected $table = 'timezones';
    use HasFactory;

    public function companyDetail(){
        return $this->hasMany(CompanyDetail::class, 'timezone_id');
    }
}
