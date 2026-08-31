<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Pendaftaran Kelas</h2></x-slot>
    <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))<div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg p-4">{{ session('status') }}</div>@endif
        @if ($errors->any())<div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4"><ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        @forelse ($pendaftaran as $p)
            <div class="bg-white shadow-sm sm:rounded-lg p-4 mb-3">
                <p class="font-medium">{{ $p->anak->nama }} — {{ $p->kelas->mataPelajaran->nama ?? '-' }} ({{ $p->kelas->hari }} {{ $p->kelas->jam_mulai }})</p>
                <p class="text-sm text-gray-500">Periode: {{ $p->periode->nama ?? '-' }} · Status: <span class="font-semibold">{{ $p->status }}</span> · Metode: {{ $p->metode_bayar }}</p>
            </div>
        @empty
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">Belum ada pendaftaran. <a href="{{ route('orang-tua.pendaftaran.create') }}" class="text-blue-600 underline">Daftar kelas sekarang</a></div>
        @endforelse
        <div class="mt-4">{{ $pendaftaran->links() }}</div>
    </div></div>
</x-app-layout>
