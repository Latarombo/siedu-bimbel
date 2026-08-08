<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Kelas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg p-4">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Daftar Kelas</h3>
                    <a href="{{ route('admin.kelas.create') }}" class="bg-gray-800 text-white text-sm px-4 py-2 rounded-md">+ Tambah</a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="text-gray-500 border-b">
                        <tr>
                            <th class="py-2">Mata Pelajaran</th>
                            <th class="py-2">Guru</th>
                            <th class="py-2">Periode</th>
                            <th class="py-2">Jadwal</th>
                            <th class="py-2">Kuota</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelas as $k)
                            <tr class="border-b">
                                <td class="py-2">{{ $k->mataPelajaran->nama }}</td>
                                <td class="py-2">{{ $k->guru->name }}</td>
                                <td class="py-2">{{ $k->periode->nama }}</td>
                                <td class="py-2">{{ $k->hari }}, {{ $k->jam_mulai }}-{{ $k->jam_selesai }}</td>
                                <td class="py-2">{{ $k->kuota_terisi }}/{{ $k->kuota_maksimum }} <span class="text-gray-400">(min {{ $k->kuota_minimum }})</span></td>
                                <td class="py-2">
                                    <span class="text-xs px-2 py-1 rounded-full @class([
                                        'bg-green-100 text-green-700' => $k->status === 'aktif',
                                        'bg-red-100 text-red-700' => $k->status === 'dibatalkan',
                                    ])">{{ $k->status }}</span>
                                </td>
                                <td class="py-2 space-x-2">
                                    <a href="{{ route('admin.kelas.edit', $k) }}" class="text-blue-600 underline">Edit</a>
                                    <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-4 text-gray-500">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $kelas->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
