<?php
namespace App\Transformers;

class PostDetailTransformer extends Transformer 
{
    private $_user_transformer;

    public function __construct() 
    {
        $this->_user_transformer = new UserTransformer();
    }

    public function transform($post) 
    {
        return [
            'id' => $post->id,
            'text' => $post->text,
            'user' => $this->_user_transformer->transform($post->user),
            'date_time' => $post->created_at,
            'comments' => $post->comments->transform(function($comment) {
                $comment_transform = new CommentTransformer();
                return $comment_transform->transform($comment);
            })
        ];
    }
}

?>