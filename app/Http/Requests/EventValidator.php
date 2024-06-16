<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventValidator extends FormRequest
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
            'title' => 'required|string|max:30|unique:events,title',
            'picture' => 'required',
            'description' => 'required|string',
            'location' => 'required|string|max:20',
            'start_time' => 'required|date|before:end_time',
            'end_time' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [ 
            'title.required' => 'Judul dibutuhkan',
            'title.string' => 'Judul harus berupa teks',
            'title.unique' => 'Judul sudah ada',
            'title.max' => 'Judul maksimal 30 karakter',
            'description.required' => 'Deskripsi dibutuhkan',
            'description.string' => 'Deskripsi harus berupa teks',
            'location.required' => ' Lokasi dibutuhkan',
            'location.string' => 'Lokasi harus berupa teks',
            'lokasi.max' => 'Lokasi maksimal 20 karakter',
            'start_time.required' => 'Waktu Mulai dibutuhkan',
            'start_time.date' => 'Format tidak valid',
            'start_time.before' => 'Waktu Mulai harus lebih awal',
            'end_time.required' => 'Waktu Selesai dibutuhkan',
            'end_time.date' => 'Format tidak valid',
        ];
    }
}
