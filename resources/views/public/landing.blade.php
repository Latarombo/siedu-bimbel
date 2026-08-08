<x-public-layout title="Siedu — Bimbingan Belajar TK-SMA">

    <section class="max-w-6xl mx-auto px-6 py-20 text-center">
        <h1 class="text-4xl font-bold mb-4">Bimbingan Belajar untuk Setiap Jenjang</h1>
        <p class="text-gray-500 max-w-xl mx-auto mb-8">
            Dari TK sampai SMA — daftar les akademik secara online, tanpa perlu datang langsung ke lembaga.
        </p>
        <a href="{{ route('katalog') }}" class="bg-gray-800 text-white px-6 py-3 rounded-md inline-block">
            Lihat Kelas Tersedia
        </a>
    </section>

    <section class="max-w-6xl mx-auto px-6 py-12">
        <h2 class="text-2xl font-semibold mb-6 text-center">Mata Pelajaran Kami</h2>

        @if ($mataPelajaran->isEmpty())
            <p class="text-center text-gray-500">Data mata pelajaran belum tersedia.</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($mataPelajaran as $mp)
                    <div class="bg-white border rounded-lg p-4">
                        <p class="font-medium">{{ $mp->nama }}</p>
                        <p class="text-sm text-gray-500">Jenjang {{ $mp->jenjang }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div>
            <p class="text-3xl font-bold">TK - SMA</p>
            <p class="text-sm text-gray-500">Semua jenjang dilayani</p>
        </div>
        <div>
            <p class="text-3xl font-bold">Online</p>
            <p class="text-sm text-gray-500">Pendaftaran & pembayaran lewat web</p>
        </div>
        <div>
            <p class="text-3xl font-bold">Aman</p>
            <p class="text-sm text-gray-500">Persetujuan Wali untuk siswa di bawah umur</p>
        </div>
    </section>

</x-public-layout>
