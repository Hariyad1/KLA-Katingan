<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <div class="w-full sm:max-w-md bg-white/95 backdrop-blur shadow-xl rounded-xl p-8 mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo_kla.png') }}" alt="Logo Kabupaten Katingan" class="h-24 mx-auto mb-3">
            {{-- <h2 class="text-2xl font-bold text-gray-800">PORTAL</h2>
            <h3 class="text-lg font-semibold text-gray-700">Kota Layak Anak</h3>
            <p class="text-sm text-gray-600">Kabupaten Katingan</p> --}}
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
                <div class="mt-2">
                    <x-text-input 
                        id="email" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition-colors" 
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                <div class="mt-2">
                    <x-text-input 
                        id="password" 
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password"
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition-colors" 
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        name="remember"
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500 transition-colors"
                    >
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                {{-- @if (Route::has('password.request'))
                    <a class="text-sm text-green-600 hover:text-green-700 font-medium" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif --}}
            </div>

            <div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                    <span>{{ __('Sign In') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>

        {{-- <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-center text-sm text-gray-500">
            </p>
            <p class="text-center text-xs text-gray-400 mt-1">
                © {{ date('Y') }} Pemerintah Kabupaten Katingan
            </p>
        </div> --}}
    </div>
</x-guest-layout>
