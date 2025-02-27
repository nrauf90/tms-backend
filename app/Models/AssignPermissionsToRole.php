<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignPermissionsToRole extends Model
{
    protected $table='role_has_permissions';
    public $timestamps = false;
    use HasFactory;
}
