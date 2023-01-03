<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
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
        return [
            'group_id' => ['required'],
            'description' => ['required'],
            'amount' => ['required'],
            'payer' => ['required'],
            'split' => ['required'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'group_id' => $this->groupId
        ]);
    }
}
