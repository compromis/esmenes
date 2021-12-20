<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Assembly;
use App\Rules\UserCanRegisterAsAssembly;

class StoreAmendmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Check amenment submission is open
        $withinDeadline = strtotime($this->assembly->amendment_deadline) > time();

        // Check if user can register as assembly
        $canRegister = ($this->input('registered_by_assembly'))
            ? $this->user()->isSpokesperson($this->assembly)
            : true;

        return $withinDeadline && $canRegister;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'article' => '',
            'title' => '',
            'original' => 'required_unless:type,addition',
            'text' => 'required_if:type,addition',
            'justification' => 'required',
            'type' => ['required', 'in:modification,deletion,addition']
        ];
    }
}
