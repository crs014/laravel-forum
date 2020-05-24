<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommonController extends ApiController
{
    /**
     * @description: Unauthorized api will redirect here.
     * @author: Cristono Wijaya
     * @return: \Illuminate\Http\Response
     */
    public function unauthorized()
    {
        $this->setStatusCode(Response::HTTP_UNAUTHORIZED);
        return $this->respond("unauthorized"); 
    }
}
