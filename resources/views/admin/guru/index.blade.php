<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Data Guru</h2>
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
                    <h3 class="font-semibold">Daftar Guru</h3>
                    <a href="{{ route('admin.guru.create') }}" class="bg-gray-800 text-white text-sm px-4 py-2 rounded-md">+ Tambah</a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="text-gray-500 border-b">
                        <tr>
                            <th class="py-2">Nama</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">No. HP</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($guru as $g)
                            <tr class="border-b">
                                <td class="py-2">{{ $g->name }}</td>
                                <td class="py-2">{{ $g->email }}</td>
                                <td class="py-2">{{ $g->phone ?? '-' }}</td>
                                <td class="py-2 space-x-2">
                                    <a href="{{ route('admin.guru.edit', $g) }}" class="text-blue-600 underline">Edit</a>
                                    <form action="{{ route('admin.guru.destroy', $g) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-gray-500">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $guru->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
