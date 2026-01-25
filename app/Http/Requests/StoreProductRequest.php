<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,draft',
            'tags' => 'nullable|string',

            // Validation cho mảng variants
            'variants' => 'required|array|min:1|max:100',
            'variants.*.option_1' => 'required|string',
            'variants.*.option_2' => 'nullable|string',
            'variants.*.option_3' => 'nullable|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.inventory_quantity' => 'required|integer|min:0',
            'variants.*.position' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'variants.min' => 'Một sản phẩm phải có ít nhất :min biến thể.',
            'variants.max' => 'Một sản phẩm không được vượt quá :max biến thể.',
            'variants.*.option_1.required' => 'Cấu hình option 1 là bắt buộc cho mỗi biến thể.',
        ];
    }
}
