<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Profil Anak</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg p-4">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Anak Terdaftar di Akun Anda</h3>
                    <a href="{{ route('orang-tua.anak.create') }}" class="bg-gray-800 text-white text-sm px-4 py-2 rounded-md">+ Tambah Anak</a>
                </div>

                @forelse ($anak as $a)
                    <div class="border-b py-3 flex justify-between items-center">
                        <div>
                            <p class="font-medium">{{ $a->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $a->tanggal_lahir->format('d M Y') }} &middot; {{ $a->jenjang_terakhir ?? 'Jenjang belum diisi' }}</p>
                        </div>
                        <div class="space-x-2">
                            <a href="{{ route('orang-tua.anak.edit', $a) }}" class="text-blue-600 underline text-sm">Edit</a>
                            <form action="{{ route('orang-tua.anak.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus profil anak ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 underline text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada anak yang ditambahkan. Klik "Tambah Anak" untuk mulai mendaftarkan les.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
