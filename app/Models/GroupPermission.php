<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use App\Models\PermissionGroup;

class GroupPermission extends Model
{   
    protected $table='group_permissions';
    use HasFactory;

    public function permissions(){
        return $this->belongsTo(Permission::class,'permission_id');
    }
    public function permissionGroup(){
        return $this->belongsTo(PermissionGroup::class, 'group_id');
    }

}
