<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GroupPermission;

class PermissionGroup extends Model
{
    protected $table='permissions_group';
    use HasFactory;

    public function groupedPermissions(){
        return $this->hasMany(GroupPermission::class,'group_id');
    }
    
}
