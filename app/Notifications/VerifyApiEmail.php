<?php
namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyApiEmail extends VerifyEmailBase
{
    /**
    * @description: Get the verification URL for the given notifiable.
    * @author: Praneet Singh Roopra
    * @source: https://medium.com/@pran.81/how-to-implement-laravels-must-verify-email-feature-in-the-api-registration-b531608ecb99
    * @param: mixed $notifiable
    * @return: string
    */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute('verificationapi.verify', 
            Carbon::now()->addMinutes(60), ['id' => $notifiable->getKey()]);
    }
}