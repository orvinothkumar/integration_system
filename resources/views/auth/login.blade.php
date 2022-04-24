<x-guest-layout>
    <x-jet-authentication-card class="bg-white">
        <x-slot name="logo">
            {{-- <x-jet-authentication-card-logo /> --}}

        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        {{-- <div class="flex justify-center logo-holder">
            <img style="width:200px" src="{{url('/images/securun_logo.jpeg')}}" alt="Logo" class="login_logo">
        </div> --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="display:table;margin: 0 auto;">
                <img style="width:150px" src="{{ url('/images/app_logo.png') }}" alt="Logo" class="login_logo">
            </div>
            <div>
                <x-jet-label for="username" value="{{ __('Username') }}" />
                <x-jet-input id="username" class="block mt-1 w-full" type="text" name="username"
                    :value="old('username')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-center mt-4">
                {{-- @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif --}}

                <x-jet-button class="ml-4 bg-red-600 hover:bg-red-500">
                    {{ __('Login') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
