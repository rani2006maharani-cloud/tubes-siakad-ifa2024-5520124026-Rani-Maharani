<?php
// app/Http/Requests/DosenRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    public function rules(): array
    {
        $dosenId = $this->route('dosen') ? $this->route('dosen')->id : null;
        
        return [
            'nidn' => 'required|string|max:20|unique:dosen,nidn,' . $dosenId,
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:dosen,email,' . $dosenId,
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nidn.required' => 'NIDN wajib diisi!',
            'nidn.unique' => 'NIDN sudah terdaftar!',
            'nama.required' => 'Nama dosen wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah terdaftar!',
        ];
    }
}