<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TrackRequest extends Request
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
                'track_title' => ['required', 'max:255' ],
                'track_number' => ['required', 'integer'],
                'file' => ['required', 'mimes:aac,mpga' ],
                'upload_id' => ['required', 'max:255' ],
            ];
    }
}
