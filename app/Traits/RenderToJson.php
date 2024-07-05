<?php

namespace App\Traits;

trait RenderToJson
{
    /**
     * The render function returns a JSON response with error information, message, and status code.
     *
     * @return A JSON response is being returned with an 'error' key containing the class name of the
     * object, a 'message' key containing the error message, and the HTTP status code based on the
     * error code of the object.
     */
    public function render(){
        return response()->json([
            'error' => class_basename($this),
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
