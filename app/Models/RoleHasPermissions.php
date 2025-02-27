<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Roles;
use App\Models\Permission;

class RoleHasPermissions extends Model
{
    protected $table = 'role_has_permissions';
    public $timestamps = false;
    use HasFactory;

    public function rolePermissions(){
        return $this->belongsTo(Roles::class, 'id');
    }
    
    public function rolesPermissions(){
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
