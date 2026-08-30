<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMataPelajaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', 'in:TK,SD,SMP,SMA'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama mata pelajaran wajib diisi.',
            'jenjang.required' => 'Jenjang wajib dipilih.',
            'jenjang.in' => 'Jenjang harus salah satu dari: TK, SD, SMP, SMA.',
        ];
    }
}
