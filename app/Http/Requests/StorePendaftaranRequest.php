<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Kelas;

class StorePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOrangTua() && $this->user()->sudahMenyetujuiConsent();
    }

    public function rules(): array
    {
        return [
            'anak_id' => ['required', 'exists:anak,id'],
            'kelas_id' => [
                'required',
                'exists:kelas,id',
                $this->validateMetodeBayar(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'anak_id.required' => 'Pilih profil anak.',
            'kelas_id.required' => 'Pilih kelas.',
            'kelas_id.validateMetodeBayar' => 'Kelas ini tidak tersedia untuk cicilan karena biaya_dp belum diatur.',
        ];
    }

    protected function validateMetodeBayar(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $kelas = Kelas::find($value);
            if ($kelas && $kelas->metode_bayar === 'dp_cicilan' && ($kelas->biaya_dp === null || $kelas->biaya_dp <= 0)) {
                $fail($this->messages()['kelas_id.validateMetodeBayar']);
            }
        };
    }
}