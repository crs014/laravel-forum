<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
        return $user->is_can_access('comment-index');
    }

    public function destroy(User $user, Comment $comment)
    {
        if($user->id == $comment->user_id) 
        {
            return $user->is_can_access('comment-destroy');
        }
        return false;
    }
}
