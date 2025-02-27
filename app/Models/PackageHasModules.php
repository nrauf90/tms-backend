<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageHasModules extends Model
{
    protected $table = 'packages_modules';
    use HasFactory;
}
