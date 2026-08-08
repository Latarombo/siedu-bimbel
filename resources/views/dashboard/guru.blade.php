<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Kelas yang Anda Ampu (Periode Aktif)</h3>

                @forelse ($kelasDiampu as $kelas)
                    <div class="border-b py-3 flex justify-between items-center">
                        <div>
                            <p class="font-medium">{{ $kelas->mataPelajaran->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $kelas->hari }}, {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}</p>
                        </div>
                        <span class="text-sm text-gray-500">{{ $kelas->kuota_terisi }}/{{ $kelas->kuota_maksimum }} siswa</span>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada kelas yang diampu pada periode aktif.</p>
                @endforelse
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-500">Menu presensi & input nilai akan ditambahkan di sini pada tahap berikutnya.</p>
            </div>
        </div>
    </div>
</x-app-layout>