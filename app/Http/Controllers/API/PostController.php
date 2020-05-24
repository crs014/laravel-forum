<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Models\Post;
use App\Models\Comment;
use App\Transformers\PostTransformer;
use App\Transformers\PostDetailTransformer;
use App\Transformers\CommentTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    /**
     * @var: App\Transformers\PostTransformer
     * @var: App\Transformers\PostDetailTransformer
     * @var: App\Transformers\CommentTransformer
     */
    protected $post_transformer;
    protected $post_detail_transformer;
    protected $comment_transformer;

    public function __construct(PostTransformer $post_transformer, 
        PostDetailTransformer $post_detail_transformer, CommentTransformer $comment_transformer) 
    {
        $this->post_transformer = $post_transformer;
        $this->post_detail_transformer = $post_detail_transformer;
        $this->comment_transformer = $comment_transformer;
    }

    /**
     * @decription: Display a listing of the resource.
     * @author: Cristono Wijaya
     * @return: \Illuminate\Http\Response
     */
    public function index()
    {
        try 
        {
            $this->authorize('index', Post::class);
            $posts = Auth::user()->posts;
            $posts->transform(function ($post) {
                return $this->post_transformer->transform($post);
            });
            $this->setStatusCode(Response::HTTP_OK);
            return $this->respond($posts);       
        }
        catch(\Exception $e) 
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

    /**
     * @decription: Store a newly created resource in storage.
     * @author: Cristono Wijaya
     * @param:  \Illuminate\Http\Request  $request
     * @return: \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try 
        {   
            $this->authorize('store', Post::class); 
            DB::beginTransaction();
            $current_user = Auth::user();
            $post = new Post();
            if(!$post->validate($request->all(), $post->validation(), $post->messages())) {
                DB::rollback();
                $this->setStatusCode(Response::HTTP_BAD_REQUEST);    
                return $this->respond($post->errors()); 
            }
            $post->text = $request->text;
            $post->user_id = $current_user->id;
        
            $post->save();
            DB::commit();
            $this->setStatusCode(Response::HTTP_CREATED);
            return $this->respond($this->post_transformer->transform($post));
        }
        catch(\Exception $e) 
        {
            DB::rollback();
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

    /**
     * @decription: Comment post.
     * @author: Cristono Wijaya
     * @param:  \Illuminate\Http\Request $request
     * @param: int $id
     * @return: \Illuminate\Http\Response
    */
    public function comment(Request $request, $id) 
    {
        try 
        {
            $this->authorize('comment', Post::class); 
            DB::beginTransaction();
            $current_user = Auth::user();
            $comment = new Comment();
            if(!$comment->validate($request->all(), $comment->validation(), $comment->messages())) {
                DB::rollback();
                $this->setStatusCode(Response::HTTP_BAD_REQUEST);    
                return $this->respond($comment->errors()); 
            }
            $comment->text = $request->text;
            $comment->user_id = $current_user->id;
            $comment->post_id = $id;
            $comment->save();
            DB::commit();
            $this->setStatusCode(Response::HTTP_CREATED);
            return $this->respond($this->comment_transformer->transform($comment));
        }
        catch(\Exception $e) 
        {
            DB::rollback();
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

    /**
     * @decription: Display the specified resource.
     * @author: Cristono Wijaya
     * @param:  int  $id
     * @return: \Illuminate\Http\Response
     */
    public function show($id)
    {
        try 
        {
            $this->authorize('show', Post::class); 
            $post = Post::find($id);
            if(!$post) {
                $this->setStatusCode(Response::HTTP_NOT_FOUND);
                return $this->respond("Not Found");
            }
            return $this->post_detail_transformer->transform($post);
        
        }
        catch(\Exception $e) 
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

    /**
     * Update the specified resource in storage.
     * @author: Cristono Wijaya
     * @param:  \Illuminate\Http\Request $request
     * @param:  int  $id
     * @return: \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try 
        {
            DB::beginTransaction();
            $post = Post::find($id);
            if(!$post) {
                $this->setStatusCode(Response::HTTP_NOT_FOUND);
                $this->authorize('update', Post::class);
                return $this->respond("Not Found");
            }
            if(!$post->validate($request->all(), $post->validation(), $post->messages())) {
                $this->setStatusCode(Response::HTTP_BAD_REQUEST);
                return $this->respond($post->errors()); 
            }
            if($post->user_id == Auth::user()->id) {
                $this->setStatusCode(Response::HTTP_OK);
                $post->text = $request->text;
                $this->authorize('update', $post);
                $post->save();
                return $this->respond($this->post_transformer->transform($post));   
            }

            $this->setStatusCode(Response::HTTP_NOT_FOUND);
            return $this->respond("Not Found");
        }
        catch(\Exception $e) 
        {
            DB::rollback();
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

    /**
     * Remove the specified resource from storage.
     * @author: Cristono Wijaya
     * @param:  int $id
     * @return: \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            $post = Post::find($id);
            if(!$post) 
            {
                $this->authorize('destroy', Post::class);
                $this->setStatusCode(Response::HTTP_NOT_FOUND);
                return $this->respond("Not Found");
            }
            $this->authorize('destroy', $post);
            $this->setStatusCode(Response::HTTP_OK);
            $post->delete();
            return $this->respond($this->post_transformer->transform($post));   

        }
        catch(\Exception $e) 
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }
}
