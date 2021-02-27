<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * 临时验证错误返回
     * @param Request $request
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws ValidationException
     */
    protected function throwValidationException(Request $request, $validator)
    {
        $response = $this->response(0,$validator->errors()->first());

        throw new ValidationException($validator,$response);
    }

    protected function response($code,$msg)
    {
        $response = [
            'code' => $code,
            'msg' => $msg,
        ];
        return $response;
    }
}
