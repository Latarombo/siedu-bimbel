@php $mp = $mp ?? null; @endphp

<div>
    <x-input-label for="nama" value="Nama Mata Pelajaran" />
    <x-text-input id="nama" name="nama" class="block mt-1 w-full" :value="old('nama', $mp->nama ?? '')" required />
    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="jenjang" value="Jenjang" />
    <select id="jenjang" name="jenjang" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        <option value="">-- Pilih Jenjang --</option>
        @foreach (['TK', 'SD', 'SMP', 'SMA'] as $j)
            <option value="{{ $j }}" @selected(old('jenjang', $mp->jenjang ?? '') === $j)>{{ $j }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('jenjang')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="deskripsi" value="Deskripsi (opsional)" />
    <textarea id="deskripsi" name="deskripsi" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('deskripsi', $mp->deskripsi ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
</div>
