<?php

namespace App\Http\Requests;

use App\Http\Traits\SanitizeRequest;
use Illuminate\Foundation\Http\FormRequest;
use Auth;

class CommonRequest extends FormRequest
{
    use SanitizeRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
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