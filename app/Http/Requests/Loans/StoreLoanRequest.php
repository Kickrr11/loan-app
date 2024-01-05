<?php

namespace App\Http\Requests\Loans;

use App\Rules\TotalAmount;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StoreLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $borrowerId = Arr::get(request()->get('user'), 'id');

        return [
            'user' => 'required',
            'amount' => ['required', 'integer', 'min:1', new TotalAmount($borrowerId)],
            'period' => 'required|integer|between:3,120',
        ];
    }
}