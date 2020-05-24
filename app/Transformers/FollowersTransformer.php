<?php
namespace App\Transformers;

use Illuminate\Support\Facades\Auth;

class FollowersTransformer extends Transformer 
{
    private $_user_transformer;

    public function __construct() 
    {
        $this->_user_transformer = new UserTransformer();
       
    }

    private function _is_followed($follow) 
    {
        $_current_user = Auth::user();
        $is_followed = $_current_user->followings
            ->where('followers', $follow->following_user->id)->first();
        if($is_followed) 
        {
            return true;
        }
        return false;
    }

    public function transform($follow) 
    {
        return [
            'user' =>  $this->_user_transformer->transform($follow->following_user),
            'is_followed' => $this->_is_followed($follow)
        ];
    }
}

?>