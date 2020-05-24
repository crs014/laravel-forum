<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = "role_permissions";
    protected $hidden = ["id"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

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

    public function role() 
    {
        return $this->belongsTo("App\Models\Role", 'role_id');
    }
    
    public function permission()
    {
        return $this->belongsTo("App\Models\Permission", 'permission_id');
    }
}
