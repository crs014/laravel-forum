<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Models\Comment;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends ApiController
{
    /**
     * @var: App\Transformers\CommentTransformer
     */
    protected $comment_transformer;

    public function __construct(CommentTransformer $comment_transformer) 
    {
        $this->comment_transformer = $comment_transformer;
    }

    /**
     * @description: Display a listing of the resource.
     * @author: Cristono Wijaya
     * @return: \Illuminate\Http\Response
     */
    public function index()
    {
        try 
        {
            $this->authorize('index', Comment::class);
            $comments = Comment::all();
            $comments->transform(function ($comment) {
                return $this->comment_transformer->transform($comment);
            });
            $this->setStatusCode(Response::HTTP_OK);
            return $this->respond($comments);
        }
        catch(\Exception $e) 
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

     /**
     * @description: Remove the specified resource from storage.
     * @author: Cristono Wijaya
     * @param: int $id
     * @return: \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            $comment = Comment::find($id);
            if(!$comment) {
                $this->setStatusCode(Response::HTTP_NOT_FOUND);
                return $this->respond("Not Found");
            }
            $this->authorize('destroy', $comment);
            $this->setStatusCode(Response::HTTP_OK);
            $comment->delete();
            return $this->respond($this->comment_transformer->transform($comment));   
        }
        catch(\Exception $e) 
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }
}
