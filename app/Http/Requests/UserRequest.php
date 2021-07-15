<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules =  [
            'firstname' => 'required',
            'lastname' => 'required'
        ];

        switch ($this->method()) {
            case 'POST':
                $rules = [
                    'email' => 'required|email|unique:users,email',
                ];

                break;

            case 'PATCH':
            case 'PUT':
                $id = null;
                $user_code = $this->segment(3);

                if ($this->segment(2) == 'update' && $user_code) {
                    $id = User::where('user_code', $user_code)->select('id')->first()->id;
                }

                $rules = [
                        'email' => 'required|email|unique:users,email,' . $id
                    ];

                break;
        }

        return $rules;
    }
}
