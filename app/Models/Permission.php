<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GroupPermission;
use App\Models\RoleHasPermissions;

class Permission extends Model
{
    protected $table = 'permissions';
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groups(){
        return $this->hasMany(GroupPermission::class);
    }

    public function permissionRoles(){
        return $this->hasMany(RoleHasPermissions::class, 'permission_id');
    }
}