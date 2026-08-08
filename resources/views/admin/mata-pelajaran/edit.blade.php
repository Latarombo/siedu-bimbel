<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Mata Pelajaran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.mata-pelajaran.update', $mataPelajaran) }}">
                    @csrf
                    @method('PUT')
                    @include('admin.mata-pelajaran._form', ['mp' => $mataPelajaran])
                    <div class="flex justify-end mt-4">
                        <x-primary-button>Perbarui</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
