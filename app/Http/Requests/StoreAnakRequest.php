<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOrangTua();
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => [
                'required',
                'date',
                'after:'.now()->subYears(100)->format('Y-m-d'),
                'before_or_equal:today',
            ],
            'jenjang_terakhir' => ['nullable', 'in:TK,SD,SMP,SMA'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama anak wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.after' => 'Tanggal lahir tidak valid.',
            'jenjang_terakhir.in' => 'Jenjang harus salah satu dari: TK, SD, SMP, SMA.',
        ];
    }
}
