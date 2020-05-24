<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Transformers\FollowersTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Models\Follow;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowController extends ApiController
{  
    /**
    * @var: App\Transformers\FollowersTransformer
    * @var: App\Transformers\UserTransformer
    */
    protected $followers_transformer;
    protected $user_transformer;

    public function __construct(FollowersTransformer $followers_transformer, UserTransformer $user_transformer) 
    {
        $this->followers_transformer = $followers_transformer;
        $this->user_transformer = $user_transformer;
    }

    /**
     * @description: Display a list followers user.
     * @author: Cristono Wijaya
     * @return: \Illuminate\Http\Response
     */
    public function followers()
    {
        try 
        {
            $this->authorize('followers', Follow::class);
            $followers = Auth::user()->followers;
            $followers->transform(function ($follow) {
                return $this->followers_transformer->transform($follow);
            });
            $this->setStatusCode(Response::HTTP_OK);
            return $this->respond($followers);
        }
        catch(\Exception $e) 
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

     /**
     * @description: Display a list following user.
     * @author: Cristono Wijaya
     * @return: \Illuminate\Http\Response
     */
    public function following() 
    {
        try 
        {
            $this->authorize('following', Follow::class);
            $followings = Auth::user()->followings;
            $followings->transform(function ($follow) {
                return $this->user_transformer->transform($follow->followers_user);
            });
            $this->setStatusCode(Response::HTTP_OK);
            return $this->respond($followings);
        }
        catch(\Exception $e) 
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

    /**
     * 
     * @description: Follow another user
     * @param: \Illuminate\Http\Request  $request
     * @param: int $id
     * @return: \Illuminate\Http\Response
     */
    public function following_user(Request $request, $id)
    {
        try 
        {
            DB::beginTransaction();
            $current_user = Auth::user();
            $follow = new Follow(); 
            $follow->followers = $id;
            $follow->following = $current_user->id;
            $this->authorize('following_user', $follow);
            $follow->save();
            DB::commit();
            $this->setStatusCode(Response::HTTP_CREATED);
            return $this->respond($this->user_transformer->transform($follow->followers_user));
        }
        catch(\Exception $e) 
        {
            DB::rollback();
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

    /**
     * @description: Unfollow another user
     * @param: int $id
     * @return: \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            $this->authorize('destroy', Follow::class);
            $current_user = Auth::user();
            $follow = Follow::where('following', $current_user->id)
                ->where('followers', $id)->first();
            if(!$follow) 
            {
                $this->setStatusCode(Response::HTTP_NOT_FOUND);
                return $this->respond("Not Found");
            }
            $this->setStatusCode(Response::HTTP_OK);
            $follow->delete();
            return $this->respond($this->user_transformer->transform($follow->followers_user));
        }
        catch(\Exception $e)  
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }
}
