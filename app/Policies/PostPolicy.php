<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $user) 
    {
        return $user->is_can_access('post-index');
    }

    public function store(User $user) 
    {
        return $user->is_can_access('post-store');
    }

    public function show(User $user) 
    {
        return $user->is_can_access('post-show');
    }

    public function update(User $user, Post $post)
    {
        if($user->id == $post->user_id) 
        {
            return $user->is_can_access('post-update');
        }
        else 
        {
            return false;
        }
    }

    public function destroy(User $user, Post $post) 
    {
        if($user->id == $post->user_id)
        {
            return $user->is_can_access('post-destroy');
        }
        else 
        {
            return false;
        }
    } 

    public function comment(User $user) 
    {
        return $user->is_can_access('post-comment');
    }
}
