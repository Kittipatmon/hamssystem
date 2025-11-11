<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust authorization logic as needed
    }

    public function rules(): array
    {
        return [
            'newto' => ['nullable','string','max:255'],
            'title' => ['required','string','max:255'],
            'content' => ['required','string'],
            'published_date' => ['required','date'],
            'is_active' => ['nullable','boolean'],
            // Legacy single image field (still accepted)
            'image' => ['nullable','image','mimes:jpg,jpeg,png,gif,svg,webp','max:10240'],
            // New: multiple images support
            'images' => ['nullable','array'],
            'images.*' => ['image','mimes:jpg,jpeg,png,gif,svg,webp','max:10240'],
        ];
    }
}
