<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Assembly;

class SupportAmendmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Have we passed the support submission deadline?
        $withinDeadline = strtotime($this->assembly->support_deadline) > time();

        // Has the user already supported the amendment?
        $notSupported = $this->amendment->supports->where('user_id', $this->user()->id)->count() === 0;

        return $withinDeadline && $notSupported;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
