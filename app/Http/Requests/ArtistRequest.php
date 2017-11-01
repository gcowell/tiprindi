<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ArtistRequest extends Request
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
                'artist_name' => ['required', 'max:255' ],
                'first_name' => ['required'],
                'last_name' => ['required'],
                'dob' => ['required'],
                'line1' => ['required'],
                'city' => ['required'],
                'postal_code' => ['required'],
                'legal_document' => ['required'],
                'stripe_token' => ['required']
            ];
    }
}
