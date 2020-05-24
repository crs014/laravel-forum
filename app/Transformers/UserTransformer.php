<?php
namespace App\Transformers;

class UserTransformer extends Transformer 
{
    public function transform($user) 
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $user->avatar_url
        ];
    }
}

?>