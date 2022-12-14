<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FormRequestUser extends FormRequest
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
        $user = auth()->user();
        $validate = array();
        $validate = $user->hasRole('master') ? ['master','manager', 'seller'] : ['seller'];

        switch ($this->method()) {
            case 'POST':
                return [
                    'name'                 => 'required|string|max:255',
                    'email'                => 'required|string|email|max:255|unique:users',
                    'password'             => 'required|string|min:6',
                    'role'                 =>  ['required', Rule::in($validate)],
                ];
                break;
            case 'PUT':
                return [
                    'name'                 => 'required|string|max:255',
                    'email'                => "required|string|email|max:255|unique:users,email,{$this->user},uuid",
                    'password'             => 'nullable|string|min:6',
                    'role'                 =>  ['required', Rule::in($validate)],
                ];
        }
    }
}
