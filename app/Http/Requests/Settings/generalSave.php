<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class generalSave extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return Auth::guard('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'CompanyName'=>"required",
            'websiteLogo'=>"required",
            'invoiceLogo'=>"required",
            'PrivatePolicy'=>"",
            'AboutUs '=>"",
            'ContcatUs'=>"",
            'TermNCondition'=>"",
            //
        ];
    }
}
