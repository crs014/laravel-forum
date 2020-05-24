<?php

namespace App\Models;

use App\Models\BaseModel;

class Comment extends BaseModel
{
    protected $table = "comments";
    protected $hidden = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text'
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

    public function post() 
    {
        return $this->belongsTo("App\Models\Post", 'post_id');
    }

    public function user() 
    {
        return $this->belongsTo("App\Models\User", 'user_id');
    }

    /**
     * Get the customized error message.
     */
    public function messages()
    {
       return [
            'text.required' => 'Text is required!',
            'text.max' => 'Text max character is 255!',
        ]; 
    }

    /**
     * Get the customized validation.
     */
    public function validation() 
    {
        return [
            'text' => 'required|max:255' 
        ];
    }
}
