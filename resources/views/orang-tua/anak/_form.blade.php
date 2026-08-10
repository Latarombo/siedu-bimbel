@php $a = $a ?? null; @endphp

<div>
    <x-input-label for="nama" value="Nama Anak" />
    <x-text-input id="nama" name="nama" class="block mt-1 w-full" :value="old('nama', $a->nama ?? '')" required />
    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
    <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="block mt-1 w-full"
        min="{{ now()->subYears(100)->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}"
        :value="old('tanggal_lahir', isset($a) ? $a->tanggal_lahir->format('Y-m-d') : '')" required />
    <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="jenjang_terakhir" value="Jenjang (opsional)" />
    <select id="jenjang_terakhir" name="jenjang_terakhir" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
        <option value="">-- Belum tahu --</option>
        @foreach (['TK', 'SD', 'SMP', 'SMA'] as $j)
            <option value="{{ $j }}" @selected(old('jenjang_terakhir', $a->jenjang_terakhir ?? '') === $j)>{{ $j }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('jenjang_terakhir')" class="mt-2" />
</div>
