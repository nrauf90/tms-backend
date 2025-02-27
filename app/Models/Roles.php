<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoleHasPermissions;
use App\Models\ModelHasRole;

class Roles extends Model
{
    protected $table = 'roles';

    use HasFactory;

    public function roleHasPermissions(){
        return $this->hasMany(RoleHasPermissions::class, 'role_id');
    }

    public function ModelHasRole(){
        return $this->hasMany(ModelHasRole::class, 'role_id');
    }
}
