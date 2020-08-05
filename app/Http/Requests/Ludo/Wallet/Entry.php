<?php

namespace App\Http\Requests\Ludo\Wallet;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class Entry extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $r)
    {
        return \App\Helper\Gate::checkUserToken($r);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
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
