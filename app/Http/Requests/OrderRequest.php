<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'menu_id' => 'required|array',
            'menu_id.*' => 'exists:menus,id',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'menu_id.required' => 'Pilih minimal satu menu.',
            'menu_id.*.exists' => 'Menu tidak valid.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.*.integer' => 'Jumlah harus berupa angka.',
            'quantity.*.min' => 'Jumlah minimal 1.',
        ];
    }
}
