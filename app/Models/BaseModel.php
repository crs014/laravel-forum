<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BaseModel extends Model 
{
    protected $errors;
    
    /**
     * Validate data with optional custom messages.
     */
    public function validate(array $data, array $validation ,array $customMessage = [])
    {
        $validator = Validator::make($data, $validation, $customMessage);

        if ($validator->fails())
        {
            $this->errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    /**
     * Get the errors from model validation.
     */
    public function errors()
    {
        return $this->errors;
    }
}

?>