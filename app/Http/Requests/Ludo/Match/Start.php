<?php

namespace App\Http\Requests\Ludo\Match;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class Start extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'id'=>'required|exists:players',
            'matchCode'=>'required|exists:matches',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $responseData=[
            'status'=>422,
            'ResponseResult'=>false,
            'ResponseMessage'=>$validator->errors()
        ];
        throw new HttpResponseException(response()->json($responseData, $responseData['status']));
    }
}
