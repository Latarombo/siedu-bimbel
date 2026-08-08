<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Siswa</p>
                    <p class="text-3xl font-bold">{{ $totalSiswa }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Guru</p>
                    <p class="text-3xl font-bold">{{ $totalGuru }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Pendaftaran Terdaftar</p>
                    <p class="text-3xl font-bold text-green-600">{{ $pendaftaranAktif }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Menunggu Pembayaran/Persetujuan</p>
                    <p class="text-3xl font-bold text-amber-600">{{ $menungguPersetujuan }}</p>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Kelola Data Master</h3>
                <div class="flex gap-4">
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="text-blue-600 underline">Mata Pelajaran</a>
                    <a href="{{ route('admin.periode.index') }}" class="text-blue-600 underline">Periode Pendaftaran</a>
                    <a href="{{ route('admin.guru.index') }}" class="text-blue-600 underline">Data Guru</a>
                    <a href="{{ route('admin.kelas.index') }}" class="text-blue-600 underline">Kelas</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>