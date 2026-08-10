<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap Orang Tua')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" :value="__('No. HP Orang Tua')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4 space-y-2">
            <label class="flex items-start gap-2 text-sm text-gray-600">
                <input type="checkbox" name="consent_privasi" value="1" class="mt-1 rounded border-gray-300" required>
                <span>Saya telah membaca dan memahami Kebijakan Privasi dan Syarat &amp; Ketentuan, dan memberikan persetujuan atas pemrosesan data pribadi saya.</span>
            </label>
            <x-input-error :messages="$errors->get('consent_privasi')" class="mt-1" />

            <label class="flex items-start gap-2 text-sm text-gray-600">
                <input type="checkbox" name="consent_wali" value="1" class="mt-1 rounded border-gray-300" required>
                <span>Saya adalah orang tua atau wali sah dari anak yang datanya akan saya daftarkan, dan memberikan persetujuan atas pemrosesan data pribadi anak tersebut.</span>
            </label>
            <x-input-error :messages="$errors->get('consent_wali')" class="mt-1" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
