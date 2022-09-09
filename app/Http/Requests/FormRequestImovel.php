<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FormRequestImovel extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name'          => 'required|string|max:255',
                    'address'       => 'required|string|max:255',
                    'description'   => 'required|string|max:255',
                    'value'         => 'required',
                ];
                break;
            case 'PUT':
                return [
                    'name'          => 'required|string|max:255',
                    'address'       => 'required|string|max:255',
                    'description'   => 'required|string|max:255',
                    'value'         => 'required',
                ];
        }
    }
}
