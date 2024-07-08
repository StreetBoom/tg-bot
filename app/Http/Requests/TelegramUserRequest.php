<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TelegramUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'username' => 'nullable|string',
            'photo' => 'nullable|array',
            'photo.big_file_id' => 'nullable|string',
        ];
    }
}
