<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Transformers\UserTransformer;
use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\VerifiesEmails;

class UserController extends ApiController
{
    
    use VerifiesEmails;
    
    /**
     * @var: App\Transformers\UserTransformer
     */
    protected $user_transformer;

    public function __construct(UserTransformer $user_transformer)
    {
        $this->user_transformer = $user_transformer;
    }

     /**
     * @decription: Login user.
     * @author: Cristono Wijaya
     * @param: \Illuminate\Http\Request $request
     * @return: \Illuminate\Http\Response
     */
    public function login(Request $request) 
    {
        try
        {
            $user = new User();
            if(!$user->validate($request->all(), $user->login_validation(), $user->messages())) {
                $this->setStatusCode(Response::HTTP_BAD_REQUEST);   
                return $this->respond($user->errors()); 
            }
            if(!Auth::attempt($request->all())) {
                $this->setStatusCode(Response::HTTP_BAD_REQUEST);    
                return $this->respond(['message' => 'login failed please check password or email']);
            }
            if(Auth::user()->email_verified_at !== null) {
                $access_token = Auth::user()->createToken('authToken')->accessToken;
                $this->setStatusCode(Response::HTTP_OK);
                return $this->respond(['access_token' => $access_token ]);
            }
            $this->setStatusCode(Response::HTTP_UNAUTHORIZED);
            return $this->respond(['message' => 'Please Verify Email']);
        }
        catch(\Exception $e) 
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

     /**
     * @decription: Register user.
     * @author: Cristono Wijaya
     * @param: \Illuminate\Http\Request $request
     * @return: \Illuminate\Http\Response
     */
    public function register(Request $request) 
    {
        try 
        {
            DB::beginTransaction();
            $user = new User();
            if(!$user->validate($request->all(), $user->validation(), $user->messages())) {
                DB::rollback();
                $this->setStatusCode(Response::HTTP_BAD_REQUEST);    
                return $this->respond($user->errors()); 
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            $user->sendApiEmailVerificationNotification();
            DB::commit();
            $this->setStatusCode(Response::HTTP_CREATED);
            return $this->respond([
                'message' => 'Please confirm yourself by clicking on verify user button sent to you on your email' 
            ]);
        }
        catch(\Exception $e) 
        {
            DB::rollback();
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }

    /**
     * @decription: get user profile.
     * @author: Cristono Wijaya
     * @return: \Illuminate\Http\Response
     */
    public function self() 
    {
        try 
        {
            $self = $this->user_transformer->transform(Auth::user());
            $this->setStatusCode(Response::HTTP_OK);
            return $this->respond($self); 
        }
         catch(\Exception $e) 
        {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respond("Error");
        }
    }
}
