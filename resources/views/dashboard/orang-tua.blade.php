<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Orang Tua</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Anak Anda</h3>
                    <a href="{{ route('orang-tua.anak.index') }}" class="text-blue-600 underline text-sm">Kelola Profil Anak</a>
                </div>

                @forelse ($anak as $a)
                    <div class="border-b py-4">
                        <p class="font-medium">{{ $a->nama }} <span class="text-sm text-gray-400 font-normal">({{ $a->tanggal_lahir->age }} tahun)</span></p>

                        @forelse ($a->pendaftaran as $p)
                            <div class="flex justify-between items-center mt-2 pl-4">
                                <span class="text-sm text-gray-600">{{ $p->kelas->mataPelajaran->nama }} — {{ $p->periode->nama }}</span>
                                <span class="text-xs px-2 py-1 rounded-full
                                    @class([
                                        'bg-green-100 text-green-700' => $p->status === 'terdaftar',
                                        'bg-amber-100 text-amber-700' => $p->status === 'menunggu_pembayaran',
                                        'bg-red-100 text-red-700' => str_starts_with($p->status, 'dibatalkan'),
                                        'bg-orange-100 text-orange-700' => $p->status === 'tertunggak',
                                    ])">
                                    {{ str_replace('_', ' ', $p->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 pl-4 mt-1">Belum ada pendaftaran untuk anak ini.</p>
                        @endforelse
                    </div>
                @empty
                    <p class="text-gray-500">Anda belum menambahkan profil anak. <a href="{{ route('orang-tua.anak.create') }}" class="text-blue-600 underline">Tambah sekarang</a>.</p>
                @endforelse
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-500">Menu pilih kelas & pendaftaran baru akan ditambahkan di sini pada tahap berikutnya.</p>
            </div>
        </div>
    </div>
</x-app-layout>
