<?php

namespace App\Http\Requests;

use App\DataTransferObjects\ResponseData;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

abstract class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $data = ResponseData::from([
            'status' => false,
            'errors' => $validator->errors()->toArray(),
        ]);

        $response = response()->json($data->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }
}
