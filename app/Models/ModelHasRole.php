<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Roles;
use App\Models\User;

class ModelHasRole extends Model
{
    protected $table = 'model_has_roles';
    use HasFactory;

    public function Roles(){
        return $this->belongsTo(Role::class);
    }
    public function roleUser(){
        return $this->belongsTo(User::class,'model_id');
    }
}
