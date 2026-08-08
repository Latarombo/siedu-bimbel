@php $k = $k ?? null; @endphp

@if (session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 text-sm">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="mata_pelajaran_id" value="Mata Pelajaran" />
        <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">-- Pilih --</option>
            @foreach ($mataPelajaran as $mp)
                <option value="{{ $mp->id }}" @selected(old('mata_pelajaran_id', $k->mata_pelajaran_id ?? '') == $mp->id)>{{ $mp->nama }} ({{ $mp->jenjang }})</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('mata_pelajaran_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="guru_id" value="Guru Pengampu" />
        <select id="guru_id" name="guru_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">-- Pilih --</option>
            @foreach ($guru as $g)
                <option value="{{ $g->id }}" @selected(old('guru_id', $k->guru_id ?? '') == $g->id)>{{ $g->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('guru_id')" class="mt-2" />
        @if ($guru->isEmpty())
            <p class="text-xs text-amber-600 mt-1">Belum ada data guru — tambahkan dulu di menu Data Guru.</p>
        @endif
    </div>
</div>

<div class="mt-4">
    <x-input-label for="periode_id" value="Periode Pendaftaran" />
    <select id="periode_id" name="periode_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        <option value="">-- Pilih --</option>
        @foreach ($periode as $p)
            <option value="{{ $p->id }}" @selected(old('periode_id', $k->periode_id ?? '') == $p->id)>{{ $p->nama }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('periode_id')" class="mt-2" />
    @if ($periode->isEmpty())
        <p class="text-xs text-amber-600 mt-1">Belum ada periode berstatus "dibuka" — tambahkan dulu di menu Periode Pendaftaran.</p>
    @endif
</div>

<div class="mt-4 grid grid-cols-3 gap-4">
    <div>
        <x-input-label for="hari" value="Hari" />
        <select id="hari" name="hari" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                <option value="{{ $h }}" @selected(old('hari', $k->hari ?? '') === $h)>{{ $h }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('hari')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="jam_mulai" value="Jam Mulai" />
        <x-text-input id="jam_mulai" name="jam_mulai" type="time" class="block mt-1 w-full" :value="old('jam_mulai', $k->jam_mulai ?? '')" required />
        <x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="jam_selesai" value="Jam Selesai" />
        <x-text-input id="jam_selesai" name="jam_selesai" type="time" class="block mt-1 w-full" :value="old('jam_selesai', $k->jam_selesai ?? '')" required />
        <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
    </div>
</div>

<div class="mt-4 grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="kuota_minimum" value="Kuota Minimum" />
        <x-text-input id="kuota_minimum" name="kuota_minimum" type="number" min="1" class="block mt-1 w-full" :value="old('kuota_minimum', $k->kuota_minimum ?? '')" required />
        <x-input-error :messages="$errors->get('kuota_minimum')" class="mt-2" />
        <p class="text-xs text-gray-500 mt-1">Business Rule #10: kelas dibatalkan otomatis + refund kalau kuota ini tidak terpenuhi saat periode ditutup.</p>
    </div>
    <div>
        <x-input-label for="kuota_maksimum" value="Kuota Maksimum" />
        <x-text-input id="kuota_maksimum" name="kuota_maksimum" type="number" min="1" class="block mt-1 w-full" :value="old('kuota_maksimum', $k->kuota_maksimum ?? '')" required />
        <x-input-error :messages="$errors->get('kuota_maksimum')" class="mt-2" />
    </div>
</div>

<div class="mt-4 grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="biaya_periode" value="Biaya Satu Periode (Rp)" />
        <x-text-input id="biaya_periode" name="biaya_periode" type="number" min="0" step="1000" class="block mt-1 w-full" :value="old('biaya_periode', $k->biaya_periode ?? '')" required />
        <x-input-error :messages="$errors->get('biaya_periode')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="biaya_dp" value="Biaya DP (opsional, Rp)" />
        <x-text-input id="biaya_dp" name="biaya_dp" type="number" min="0" step="1000" class="block mt-1 w-full" :value="old('biaya_dp', $k->biaya_dp ?? '')" />
        <x-input-error :messages="$errors->get('biaya_dp')" class="mt-2" />
        <p class="text-xs text-gray-500 mt-1">F6: kosongkan kalau kelas ini hanya bisa dibayar lunas.</p>
    </div>
</div>

@if (isset($k))
    <div class="mt-4 text-sm text-gray-500">
        Kuota terisi saat ini: <strong>{{ $k->kuota_terisi }}</strong> (terkunci otomatis oleh sistem pendaftaran, tidak bisa diedit manual)
    </div>
@endif
