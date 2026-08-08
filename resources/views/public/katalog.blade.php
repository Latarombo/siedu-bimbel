<x-public-layout title="Katalog Kelas — Siedu">

    <section class="max-w-6xl mx-auto px-6 py-12">
        <h1 class="text-2xl font-semibold mb-6">Katalog Kelas</h1>

        @if ($kelas->isEmpty())
            <p class="text-gray-500">Belum ada kelas yang dibuka untuk periode saat ini.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($kelas as $k)
                    <div class="bg-white border rounded-lg p-4">
                        <p class="font-medium">{{ $k->mataPelajaran->nama }}</p>
                        <p class="text-sm text-gray-500">{{ $k->mataPelajaran->jenjang }} · {{ $k->periode->nama }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ $k->hari }}, {{ $k->jam_mulai }}-{{ $k->jam_selesai }}</p>

                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $k->kuotaPenuh() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $k->kuotaPenuh() ? 'Kuota Penuh' : $k->kuota_maksimum - $k->kuota_terisi.' slot tersisa' }}
                            </span>
                            <span class="text-sm font-medium">Rp{{ number_format($k->biaya_periode, 0, ',', '.') }}</span>
                        </div>

                        @auth
                            @if (! $k->kuotaPenuh())
                                <a href="{{ route('dashboard') }}" class="block text-center mt-3 bg-gray-800 text-white text-sm py-2 rounded-md">
                                    Daftar Lewat Dashboard
                                </a>
                            @endif
                        @else
                            @if (! $k->kuotaPenuh())
                                <a href="{{ route('register') }}" class="block text-center mt-3 bg-gray-800 text-white text-sm py-2 rounded-md">
                                    Daftar Akun untuk Mulai
                                </a>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $kelas->links() }}</div>
        @endif
    </section>

</x-public-layout>
