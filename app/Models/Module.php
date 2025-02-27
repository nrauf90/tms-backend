<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Package;

class Module extends Model
{   
    protected $table = 'modules';
    use HasFactory;

    public function packages(){
        return $this->belongsTo(Package::class, 'packages_modules');
    }

}
