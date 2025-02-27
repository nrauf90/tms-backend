<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyDetail;

class Language extends Model
{
    protected $table = 'languages';
    use HasFactory;

    public function companyDetail(){
        return $this->hasMany(CompanyDetail::class, 'language_id');
    }
}
