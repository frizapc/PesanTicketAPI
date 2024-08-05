<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventValidator extends FormRequest
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
            'start_time' => 'required|date|before:end_time|after:'. now()->addHour()->format('Y-m-d H:i:s'),
            'end_time' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [ 
            'start_time.required' => 'Waktu Mulai dibutuhkan',
            'start_time.date' => 'Format tidak valid',
            'start_time.after' => 'Waktu Mulai minimal 1 jam dari sekarang',
            'start_time.before' => 'Waktu Mulai harus lebih awal',
            'end_time.required' => 'Waktu Selesai dibutuhkan',
            'end_time.date' => 'Format tidak valid'
        ];
    }
}
