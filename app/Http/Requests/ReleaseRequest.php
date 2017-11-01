<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ReleaseRequest extends Request
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

                'release_title' => ['required', 'max:255' ],
                'release_date' => ['required', 'date'],
                'release_type' => ['required', 'valid_release_type'],
//                'image_path' =>

            ];
    }
}
