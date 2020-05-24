<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationApiController extends Controller
{
    use VerifiesEmails;

    /**
     * @decription: Verify or actived user login 
     * @author: Praneet Singh Roopra
     * @source: https://medium.com/@pran.81/how-to-implement-laravels-must-verify-email-feature-in-the-api-registration-b531608ecb99
     * @param:  \Illuminate\Http\Request  $request
     * @return: \Illuminate\Http\Response
     */
    public function verify(Request $request) 
    {
        try 
        {
            $userID = $request['id'];
            $user = User::findOrFail($userID);
            $date = date('Y-m-d g:i:s');
            $user->email_verified_at = $date;
            $user->save();
            return response()->json('Email verified!');
        }
        catch(\Exception $e) 
        {
            return response()->json('Bad Request', 400);
        }
    }

    /**
     * @decription: Verify or actived user login 
     * @author: Praneet Singh Roopra
     * @source: https://medium.com/@pran.81/how-to-implement-laravels-must-verify-email-feature-in-the-api-registration-b531608ecb99
     * @param: \Illuminate\Http\Request  $request
     * @return: \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        try 
        {
            if ($request->user()->hasVerifiedEmail()) {
                return response()->json('User already have verified email!', 422);
            }
            $request->user()->sendEmailVerificationNotification();
            return response()->json('The notification has been resubmitted');
        }
        catch(\Exception $e)
        {
            return response()->json('Bad Request');
        }
    }
}
