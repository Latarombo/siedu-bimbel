<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Periode Pendaftaran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg p-4">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Daftar Periode</h3>
                    <a href="{{ route('admin.periode.create') }}" class="bg-gray-800 text-white text-sm px-4 py-2 rounded-md">+ Tambah</a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="text-gray-500 border-b">
                        <tr>
                            <th class="py-2">Nama</th>
                            <th class="py-2">Mulai</th>
                            <th class="py-2">Selesai</th>
                            <th class="py-2">Tutup Daftar</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($periode as $p)
                            <tr class="border-b">
                                <td class="py-2">{{ $p->nama }}</td>
                                <td class="py-2">{{ $p->tanggal_mulai->format('d M Y') }}</td>
                                <td class="py-2">{{ $p->tanggal_selesai->format('d M Y') }}</td>
                                <td class="py-2">{{ $p->tanggal_tutup_pendaftaran->format('d M Y') }}</td>
                                <td class="py-2">
                                    <span class="text-xs px-2 py-1 rounded-full @class([
                                        'bg-green-100 text-green-700' => $p->status === 'dibuka',
                                        'bg-gray-100 text-gray-700' => $p->status === 'ditutup',
                                        'bg-blue-100 text-blue-700' => $p->status === 'selesai',
                                    ])">{{ $p->status }}</span>
                                </td>
                                <td class="py-2 space-x-2">
                                    <a href="{{ route('admin.periode.edit', $p) }}" class="text-blue-600 underline">Edit</a>
                                    <form action="{{ route('admin.periode.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-4 text-gray-500">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $periode->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
