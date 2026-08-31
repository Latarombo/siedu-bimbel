<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Daftar Kelas</h2></x-slot>
    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        @if($errors->any())<div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4"><ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div class="bg-white shadow sm:rounded-lg p-6">
            @if($anak->isEmpty())<p class="text-gray-600">Belum ada anak. <a href="{{ route('orang-tua.anak.create') }}" class="text-blue-600 underline">Tambah anak dulu</a>.</p>
            @else
            <form method="POST" action="{{ route('orang-tua.pendaftaran.store') }}" id="form-daftar">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Pilih Anak</label>
                    <select name="anak_id" id="anak_id" class="mt-1 w-full border-gray-300 rounded-md" required>
                        <option value="">-- pilih --</option>
                        @foreach($anak as $a)<option value="{{ $a->id }}" data-jenjang="{{ $a->jenjang_terakhir ?? '' }}">{{ $a->nama }} ({{ $a->jenjang_terakhir ?? 'jenjang kosong' }})</option>@endforeach
                    </select>
                    <p id="warn-jenjang" class="hidden text-sm text-amber-600 mt-1">Lengkapi jenjang di profil anak dulu. <a href="{{ route('orang-tua.anak.index') }}" class="underline">Ke profil anak</a></p>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium">Pilih Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="mt-1 w-full border-gray-300 rounded-md" required>
                        <option value="">-- pilih anak dulu --</option>
                        @foreach($kelas as $k)<option value="{{ $k->id }}" data-jenjang="{{ $k->mataPelajaran->jenjang ?? '' }}" data-biaya="{{ $k->biaya_periode }}" data-dp="{{ $k->biaya_dp ?? '' }}">{{ $k->mataPelajaran->nama ?? 'Kelas' }} ({{ $k->mataPelajaran->jenjang ?? '-' }}) — {{ $k->hari }} {{ substr($k->jam_mulai,0,5) }}-{{ substr($k->jam_selesai,0,5) }} · Sisa {{ $k->kuota_maksimum - $k->kuota_terisi }} · Rp{{ number_format($k->biaya_periode,0,',','.') }}{{ $k->biaya_dp ? ' DP '.number_format($k->biaya_dp,0,',','.') : ' (lunas)' }}</option>@endforeach
                    </select>
                    <p id="no-kelas" class="hidden text-sm text-gray-500 mt-1">Tidak ada kelas sesuai jenjang anak ini.</p>
                </div>
                <div class="mt-6 flex justify-end"><x-primary-button>Daftar</x-primary-button></div>
            </form>
            @endif
        </div>
    </div>
    <script>
    (function(){
        const anakSel=document.getElementById('anak_id'), kelasSel=document.getElementById('kelas_id'), warn=document.getElementById('warn-jenjang'), noKelas=document.getElementById('no-kelas');
        const allOpts=[...kelasSel.options].slice(1);
        function filter(){
            const jenjang=(anakSel.selectedOptions[0]?.dataset.jenjang)||'';
            warn.classList.toggle('hidden', !!jenjang);
            kelasSel.innerHTML='<option value="">-- pilih kelas --</option>';
            let shown=0;
            allOpts.forEach(o=>{
                const kj=o.dataset.jenjang;
                if(!jenjang || kj===jenjang){ kelasSel.appendChild(o.cloneNode(true)); shown++; }
            });
            noKelas.classList.toggle('hidden', !(jenjang && shown===0));
            kelasSel.disabled = !jenjang;
            if(!jenjang) noKelas.classList.add('hidden');
        }
        anakSel.addEventListener('change', filter);
        filter();
    })();
    </script>
</x-app-layout>
