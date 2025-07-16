<x-guest-layout>
    <form method="POST" action="{{ route('magic.login.request') }}">
        @csrf

        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" type="email" name="email" required autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />

        <x-primary-button class="mt-4">
            {{ __('Send Magic Link') }}
        </x-primary-button>
    </form>
</x-guest-layout>
