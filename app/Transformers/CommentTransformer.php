<?php
namespace App\Transformers;

class CommentTransformer extends Transformer 
{
    private $_user_transformer;

    public function __construct() 
    {
        $this->_user_transformer = new UserTransformer();
    }

    public function transform($comment) 
    {
        return [
            'id' => $comment->id,
            'text' => $comment->text,
            'user' =>  $this->_user_transformer->transform($comment->user),
            'date_time' => $comment->created_at
        ];
    }
}

?>