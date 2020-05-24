<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Auth\Access\HandlesAuthorization;

class FollowPolicy
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

    public function followers(User $user) 
    {
        return $user->is_can_access('follow-followers');
    }

    public function following(User $user)
    {
        return $user->is_can_access('follow-following');
    }

    public function following_user(User $user, Follow $follow)
    {
        if($follow->followers != $follow->following)
        {
            return $user->is_can_access('follow-following-user');
        }
        return false;
    }

    public function destroy(User $user)
    {
        return $user->is_can_access('follow-destroy');
    }
}
