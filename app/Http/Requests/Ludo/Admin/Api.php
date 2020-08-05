<?php

namespace App\Http\Requests\Ludo\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
class Api extends FormRequest
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
}
