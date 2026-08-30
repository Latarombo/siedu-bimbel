<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Kelas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Kelas::class);
    }

    public function rules(): array
    {
        return [
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajaran,id'],
            'guru_id' => ['required', 'exists:users,id'],
            'periode_id' => ['required', 'exists:periode_pendaftaran,id'],
            'kuota_maksimum' => ['required', 'integer', 'min:1'],
            'kuota_minimum' => ['required', 'integer', 'min:1', 'lte:kuota_maksimum'],
            'hari' => ['required', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'biaya_periode' => ['required', 'numeric', 'min:0'],
            'biaya_dp' => ['nullable', 'numeric', 'min:0', 'lte:biaya_periode'],
        ];
    }

    public function messages(): array
    {
        return [
            'mata_pelajaran_id.required' => 'Mata pelajaran wajib dipilih.',
            'guru_id.required' => 'Guru wajib dipilih.',
            'periode_id.required' => 'Periode pendaftaran wajib dipilih.',
            'kuota_maksimum.min' => 'Kuota maksimum minimal 1.',
            'kuota_minimum.lte' => 'Kuota minimum harus lebih kecil atau sama dengan kuota maksimum.',
            'hari.required' => 'Hari wajib diisi.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'biaya_dp.lte' => 'Biaya DP tidak boleh melebihi biaya per periode.',
        ];
    }
}
