<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Package;
use App\Models\CompanyDetail;

class Company extends Model
{
    protected $table = 'companies';
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function package(){
        return $this->belongsTo(Package::class);
    }

    public function companyDetail(){
        return $this->hasOne(CompanyDetail::class, 'company_id');
    }
}
