@extends('layouts.guest')

@section('content')

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="">
            <label for="email" class="">{{ __('Email Address') }}</label>

            <div class="">
                <input id="email" type="email" class="w-full p-2 my-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="">
            <label for="password" class="">{{ __('Contraseña') }}</label>

            <div class="">
                <input id="password" type="password" class="w-full p-2 my-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="">
            <div class="">
                <button type="submit" class="w-full px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Iniciar sesión') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
            </div>
        </div>
    </form>
      
@endsection
