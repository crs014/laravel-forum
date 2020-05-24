<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use App\Notifications\VerifyApiEmail;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = "users";
    protected $errors;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_url',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

    public function user_roles() 
    {
        return $this->hasMany('App\Models\UserRole');    
    }

    public function comments() 
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function posts() 
    {
        return $this->hasMany('App\Models\Post');
    }

    public function roles() 
    {
        return $this->belongsToMany('App\Models\Role');
    }
    
    public function followings_user() 
    {
        return $this->belongsToMany('App\Models\User', 'following');
    }

    public function followers_user() 
    {
        return $this->belongsToMany('App\Models\User', 'followers');
    }

    public function followers() 
    {
        return $this->hasMany('App\Models\Follow', 'followers', 'id');
    }

    public function followings() 
    {
        return $this->hasMany('App\Models\Follow', 'following', 'id');
    }

    public function is_can_access($permission_name) 
    {
        $user_roles = $this->user_roles;
        $is_can_access = false;
        
        foreach($user_roles as $user_role) 
        {
            if(!$user_role->role->permissions->where('name', $permission_name)->first()){
                $is_can_access = true;
                break;
            }
        }
        
        return $is_can_access;
    }

    /**
     * Get the customized error message.
     */
    public function messages()
    {
       return [
            'text.required' => 'Text is required!.',
            'text.max' => 'Text max character is 255!.',
        ]; 
    }
    
    /**
     * Get the customized login validation.
     */
    public function login_validation() 
    {
        return [
            'email' => 'required|max:255|email',
            'password' => 'required|min:8'
        ];
    }

    /**
     * Get the customized register validation.
     */
    public function validation() 
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
 
     /**
     * Validate data with optional custom messages.
     */
    public function validate(array $data, array $validation ,array $customMessage = [])
    {
        $validator = Validator::make($data, $validation, $customMessage);

        if ($validator->fails())
        {
            $this->errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    /**
     * Get the errors from model validation.
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * @description: Send email vertification 
     * @author: Praneet Singh Roopra
     * @source: https://medium.com/@pran.81/how-to-implement-laravels-must-verify-email-feature-in-the-api-registration-b531608ecb99
     * @return: mixed
     */
    public function sendApiEmailVerificationNotification()
    {
        $this->notify(new VerifyApiEmail); 
    }
}
