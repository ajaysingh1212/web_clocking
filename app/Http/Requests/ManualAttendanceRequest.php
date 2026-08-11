<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManualAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required'],
            'date' => ['required', 'date'],
            'action' => ['required', 'in:in,out'],
            'time' => ['required', 'date_format:H:i'],
            'image' => ['required', 'image', 'max:5120'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'location' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Unable to identify the user for manual attendance.',
            'date.required' => 'Please choose a date.',
            'date.date' => 'The selected date is invalid.',
            'action.required' => 'Please choose punch action.',
            'action.in' => 'The selected action is invalid.',
            'time.required' => 'Please choose a time.',
            'time.date_format' => 'The selected time must use the format HH:MM.',
            'image.required' => 'Please capture a photo before submitting.',
            'image.image' => 'The uploaded file must be an image.',
            'image.max' => 'The uploaded image must be smaller than 5 MB.',
            'latitude.required' => 'Location not selected yet. Choose a location from the map.',
            'longitude.required' => 'Location not selected yet. Choose a location from the map.',
            'location.required' => 'Location not selected yet. Choose a location from the map.',
        ];
    }
}
