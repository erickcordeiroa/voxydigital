<?php

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['required', 'image', 'max:2048'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'link_text' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'image.required' => 'A imagem é obrigatória.',
            'image.image' => 'O arquivo deve ser uma imagem.',
            'image.max' => 'A imagem não pode ser maior que 2MB.',
            'link_url.url' => 'O link deve ser uma URL válida.',
            'link_url.max' => 'O link não pode ter mais de 255 caracteres.',
            'link_text.max' => 'O texto do link não pode ter mais de 50 caracteres.',
            'sort_order.integer' => 'A ordem deve ser um número inteiro.',
            'sort_order.min' => 'A ordem deve ser maior ou igual a 0.',
            'starts_at.date' => 'A data de início deve ser uma data válida.',
            'ends_at.date' => 'A data de fim deve ser uma data válida.',
            'ends_at.after' => 'A data de fim deve ser posterior à data de início.',
        ];
    }
}