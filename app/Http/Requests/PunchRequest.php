<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'max:5120'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'location' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Please capture a photo before submitting.',
            'latitude.required' => 'Location not captured yet. Tap refresh and try again.',
            'longitude.required' => 'Location not captured yet. Tap refresh and try again.',
            'location.required' => 'Location not captured yet. Tap refresh and try again.',
        ];
    }
}