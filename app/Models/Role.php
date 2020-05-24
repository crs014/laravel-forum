<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";
    protected $hidden = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that are errors from model validation.
     *
     * @var errors
     */
    protected $errors;

    public function user_roles() 
    {
        return $this->hasMany('App\Models\UserRole');
    }

    public function role_permissions()
    {
        return $this->hasMany('App\Models\RolePermission');
    }

    public function permissions() 
    {
        return $this->belongsToMany('App\Models\Permission','role_permissions');
    }

    public function users() 
    {
        return $this->belongsToMany("App\Models\User");
    }

}
