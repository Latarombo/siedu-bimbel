<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Data Guru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.guru.update', $guru) }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" value="Nama" />
                        <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $guru->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email', $guru->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="phone" value="No. HP (opsional)" />
                        <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone', $guru->phone)" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" value="Password Baru (kosongkan jika tidak diubah)" />
                        <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex justify-end mt-4">
                        <x-primary-button>Perbarui</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
