<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = "follows";
    protected $hidden = ['id'];

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

    public function following_user() 
    {
        return $this->belongsTo("App\Models\User", 'following');
    }

    public function followers_user() 
    {
        return $this->belongsTo("App\Models\User", 'followers');
    }
}
