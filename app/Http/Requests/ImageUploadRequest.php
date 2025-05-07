<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageUploadRequest extends FormRequest
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
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'You must upload at least one image.',
            'images.array' => 'The images field must be an array.',
            'images.max' => 'You may not upload more than 5 images.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.mimes' => 'Images must be in JPG, JPEG, or PNG format.',
            'images.*.max' => 'Each image must not exceed 5MB.',
        ];
    }
}
