<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($butuhPersetujuanWali->isNotEmpty())
                <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-4">
                    Ada pendaftaran yang menunggu persetujuan Wali. Silakan minta Wali Anda menyetujui lewat link yang dikirim ke kontak terdaftar.
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Status Pendaftaran Anda</h3>

                @forelse ($pendaftaran as $p)
                    <div class="border-b py-3 flex justify-between items-center">
                        <div>
                            <p class="font-medium">{{ $p->kelas->mataPelajaran->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $p->periode->nama }}</p>
                        </div>
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
                    <p class="text-gray-500">Anda belum mendaftar kelas apa pun.</p>
                @endforelse
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-500">Menu pilih kelas & pendaftaran baru akan ditambahkan di sini pada tahap berikutnya.</p>
            </div>
        </div>
    </div>
</x-app-layout>