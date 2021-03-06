<?php

namespace App\Http\Requests;

use App\Http\Traits\SanitizeRequest;
use Illuminate\Foundation\Http\FormRequest;
use Auth;

class ApplicationRequest extends FormRequest
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
            'name' => 'required',
            'application_url' => 'required|url',
            'application_type' => 'required'
        ];
    }
}
