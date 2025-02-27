<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Module;
use App\Models\Company;

class Package extends Model
{
    protected $table = 'packages';
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function modules(){
        return $this->belongsToMany(Module::class, 'packages_modules');
    }

    public function companies(){
        return $this->hasMany(Company::class, 'package_id');
    }
}
