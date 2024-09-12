<?php

namespace App\Http\Requests;

use App\Rules\Rifa as RulesRifa;
use App\Rules\RifaQuantity;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required|max:64|min:3|regex:/^(?:[A-zÀ-ü]{3,}).*(?:[A-zÀ-ü]{3,})$/i',
            'email' => 'required|email|max:100',
            'telephone' => 'required|min:10|max:20',
            'confirmTelephone' => 'required|same:telephone',
            'terms' => 'required|accepted',
            'quantity' => ['required', 'integer', new RifaQuantity],
            'rifa' => ['required', new RulesRifa],
        ];
    }
}
