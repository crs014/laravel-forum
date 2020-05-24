<?php
namespace App\Transformers;

use Illuminate\Support\Facades\Auth;

class FollowingTransformer extends Transformer 
{
    private $_user_transformer;
    private $_current_user;

    public function __construct() 
    {
        $this->_user_transformer = new UserTransformer();
        $this->_current_user = Auth::user();
    }

    public function transform($follow) 
    {
        return [
            'user' =>  $this->_user_transformer->transform($follow->following),
            'is_followed' => function() {
                $is_followed = $this->_current_user->following
                    ->where('followers', $follow->id)->get();
                if($is_followed) {
                    return true;
                }
                return false;
            }
        ];
    }
}

?>