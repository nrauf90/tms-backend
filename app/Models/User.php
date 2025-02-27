<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use Spatie\Permission\Traits\HasRoles;
use App\Models\ModelHasRole;
use App\Models\Notification;
use App\Models\Package;
use App\Models\StripeCredential;
use App\Models\PaypalCredential;
use App\Models\Company;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    /* protected $fillable = [
        'fname',
        'lname',
        'email',
        'password',
    ];*/
    protected $guard_name = 'api';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'api_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getFullName()
    {
        return "{$this->fname} {$this->lname}";
    }
    /**
     * Get the user's Ability.
     *
     * @return string
     */
    public function getAbility()
    {
        $permissions = $this->getAllPermissions();
        $i = 0;
        foreach ($permissions as $permission) {
            $name = $permission->name;
            $ability = explode('-', $name);
            $action = $ability[0];
            $resource = $ability[1];
            $permissions[$i]->action = $action;
            $permissions[$i]->resource = $resource;
            $i++;
        }
        return $permissions;
    }




    public function getAbilitiesName()
    {
        $permissions = $this->getAllPermissions();
        $permissionsNames = [];
        $i = 0;
        foreach ($permissions as $permission) {
            $permissionsNames[$i] = $permission->name;
            $i++;
        }
        return $permissionsNames;
    }

    public function userRole() {
        return $this->hasMany(ModelHasRole::class, 'model_id');
    }

    public function notifications() {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function packages(){
        return $this->hasMany(Package::class, 'user_id');
    }

    public function stripe(){
        return $this->hasOne(StripeCredential::class, 'user_id');
    }

    public function paypal(){
        return $this->hasOne(PaypalCredential::class, 'user_id');
    }

    public function company(){
        return $this->hasMany(Company::class, 'user_id');
    }
}
