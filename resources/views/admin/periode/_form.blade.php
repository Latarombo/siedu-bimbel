@php $p = $p ?? null; @endphp

<div>
    <x-input-label for="nama" value="Nama Periode" />
    <x-text-input id="nama" name="nama" class="block mt-1 w-full" :value="old('nama', $p->nama ?? '')" placeholder="Contoh: Tahun Ajaran 2026/2027" required />
    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
</div>

<div class="mt-4 grid grid-cols-3 gap-4">
    <div>
        <x-input-label for="tanggal_tutup_pendaftaran" value="Tutup Pendaftaran" />
        <x-text-input id="tanggal_tutup_pendaftaran" name="tanggal_tutup_pendaftaran" type="date" class="block mt-1 w-full" :value="old('tanggal_tutup_pendaftaran', isset($p) ? $p->tanggal_tutup_pendaftaran->format('Y-m-d') : '')" required />
        <x-input-error :messages="$errors->get('tanggal_tutup_pendaftaran')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
        <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date" class="block mt-1 w-full" :value="old('tanggal_mulai', isset($p) ? $p->tanggal_mulai->format('Y-m-d') : '')" required />
        <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="tanggal_selesai" value="Tanggal Selesai" />
        <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date" class="block mt-1 w-full" :value="old('tanggal_selesai', isset($p) ? $p->tanggal_selesai->format('Y-m-d') : '')" required />
        <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="status" value="Status" />
    <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        @foreach (['dibuka' => 'Dibuka', 'ditutup' => 'Ditutup', 'selesai' => 'Selesai'] as $val => $label)
            <option value="{{ $val }}" @selected(old('status', $p->status ?? 'dibuka') === $val)>{{ $label }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
    <p class="text-xs text-gray-500 mt-1">Business Rule #10: kuota minimum tiap kelas dicek otomatis saat status diubah ke "ditutup".</p>
</div>
