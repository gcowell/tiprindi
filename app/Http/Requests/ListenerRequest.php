<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ListenerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return
            [

                'currency' => [ 'required', 'valid_currency'],
                'like_value' => ['required', 'numeric', 'min:0.01', 'max:99999'],
                'love_value' => ['required', 'numeric', 'min:0.01', 'max:99999', 'greater_than:like_value'],
                'stripe_token' => ['required']

            ];
    }
}
