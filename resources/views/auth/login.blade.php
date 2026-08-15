@extends('layouts.auth')

@section('title', 'PSARECO Login')

@section('content')
<div class="min-h-screen bg-[#d2e8d9] flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-emerald-100 p-6 sm:p-8 transition-all">

        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="w-20 h-auto mx-auto drop-shadow-sm">
            </a>
            <h1 class="mt-3 mb-3 text-3xl font-bold tracking-tight text-[#2c7a56]">PSARECO</h1>
            <p class="text-xs font-bold text-slate-600 mt-1 mb-3 uppercase tracking-wide">Farm Resource Management System</p>
            <p class="text-[11px] text-slate-400 mt-0.5 mb-3">Machinery Scheduling & Inventory Monitoring</p>
        </div>

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="mb-5 bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 text-red-800 shadow-sm relative">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-sm"></i>
                        <div>
                            <h4 class="font-bold text-sm">Login Error</h4>
                            <ul class="mt-1 space-y-1 text-xs text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button @click="show = false" type="button" class="text-red-400 hover:text-red-600 transition-colors p-1">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="mb-5 bg-emerald-50 border-l-4 border-emerald-600 rounded-r-xl p-4 text-emerald-900 shadow-sm relative">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-check-circle text-emerald-600 mt-0.5 text-sm"></i>
                        <div>
                            <h4 class="font-bold text-sm">Success</h4>
                            <p class="mt-1 text-xs text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" type="button" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Form Container -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div class="relative">
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="off" placeholder=" "
                       class="peer w-full px-4 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border @error('email') border-red-500 focus:ring-red-500 @else border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68] @enderror rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all">

                <label for="email"
                       class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none">
                    <i class="fas fa-envelope text-slate-400 peer-focus:text-[#2c7a56]"></i>
                    <span>Email Address</span>
                </label>

                @error('email')
                    <p class="mt-1 text-xs text-red-500 font-medium flex items-center space-x-1">
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div x-data="{ showPassword: false }" class="relative">
                <div class="relative flex items-center">
                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required autocomplete="current-password" placeholder=" "
                           class="peer w-full pl-4 pr-12 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border @error('password') border-red-500 focus:ring-red-500 @else border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68] @enderror rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all">

                    <label for="password"
                           class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none">
                        <i class="fas fa-lock text-slate-400 peer-focus:text-[#2c7a56]"></i>
                        <span>Password</span>
                    </label>

                    <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-3 text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors focus:outline-none">
                        <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>

                @error('password')
                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full mt-2 bg-[#3d8b68] hover:bg-[#327356] active:bg-[#276447] text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 text-sm">
                <i class="fas fa-sign-in-alt text-xs"></i>
                <span>Login</span>
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-xs text-slate-500">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-[#2c7a56] font-bold hover:underline">Register here</a>
            </p>
        </div>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
        </div>

        <div class="text-center mt-3">
            <p class="text-[11px] text-slate-400 italic flex items-center justify-center space-x-1 mt-2">
                <i class="fas fa-copyright text-[10px]"></i>
                <span>2026 PSARECO Cooperative</span>
            </p>
        </div>

    </div>
</div>
@endsection
