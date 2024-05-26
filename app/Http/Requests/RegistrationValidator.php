<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationValidator extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama dibutuhkan',
            'email.required' => 'Email dibutuhkan',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah ada',
            'password.required' => 'Password dibutuhkan',
            'password.min' => 'Password minimal 6 karakter',
        ];
    }
}
