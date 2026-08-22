@extends('layouts.auth')

@section('title', 'PSARECO Login')

@section('content')
    <div class="min-h-screen bg-[#d2e8d9] flex items-center justify-center p-0 sm:p-4 lg:p-6 overflow-hidden">
        <div class="relative w-full max-w-6xl min-h-screen sm:min-h-0 lg:min-h-[680px] bg-white/80 backdrop-blur-sm lg:rounded-3xl shadow-2xl border border-white/70 overflow-hidden flex flex-col lg:flex-row">
            <div class="relative hidden lg:flex lg:w-[55%] overflow-hidden bg-gradient-to-br from-[#2c7a56] via-[#3d8b68] to-[#286347] text-white">
                <div class="absolute -right-20 -bottom-28 w-[420px] h-[420px] opacity-10 pointer-events-none transform -rotate-12 select-none z-0">
                    <img src="{{ asset('assets/images/PSARECO_logo.png') }}"  alt="" class="w-full h-full object-contain filter brightness-200" >
                </div>
                <div class="absolute inset-0 overflow-hidden z-0">
                    <style>
                        @keyframes float1 { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(20px, -20px); } }
                        @keyframes float2 { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-25px, 15px); } }
                        @keyframes float3 { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(30px, 20px); } }
                        @keyframes float4 { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-15px, -25px); } }
                        @keyframes glow { 0%, 100% { opacity: 0.6; } 50% { opacity: 1; } }
                        .particle { animation-timing-function: ease-in-out; }
                        .particle1 { animation: float1 6s infinite; }
                        .particle2 { animation: float2 8s infinite; }
                        .particle3 { animation: float3 7s infinite; }
                        .particle4 { animation: float4 9s infinite; }
                        .glow { animation: glow 3s infinite; }
                    </style>

                    <div class="absolute top-[20%] left-[15%] w-3 h-3 bg-white rounded-full particle particle1 shadow-[0_0_20px_rgba(255,255,255,0.8)]"></div>
                    <div class="absolute top-[40%] left-[10%] w-2 h-2 bg-emerald-200 rounded-full particle particle2 shadow-[0_0_15px_rgba(167,243,208,0.6)]"></div>
                    <div class="absolute top-[60%] right-[20%] w-2.5 h-2.5 bg-white rounded-full particle particle3 shadow-[0_0_18px_rgba(255,255,255,0.7)]"></div>
                    <div class="absolute top-[35%] right-[15%] w-1.5 h-1.5 bg-yellow-200 rounded-full particle particle4 glow shadow-[0_0_25px_rgba(253,224,71,0.6)]"></div>
                    <div class="absolute bottom-[25%] left-[20%] w-2 h-2 bg-white rounded-full particle particle1 shadow-[0_0_16px_rgba(255,255,255,0.6)]"></div>
                    <div class="absolute bottom-[40%] right-[25%] w-2.5 h-2.5 bg-emerald-200 rounded-full particle particle2 shadow-[0_0_20px_rgba(167,243,208,0.5)]"></div>
                    <div class="absolute top-[50%] left-[50%] w-1.5 h-1.5 bg-white rounded-full particle particle3 shadow-[0_0_14px_rgba(255,255,255,0.5)]"></div>
                    <div class="absolute bottom-[30%] left-[60%] w-2 h-2 bg-yellow-300 rounded-full particle particle4 glow shadow-[0_0_22px_rgba(253,224,71,0.7)]"></div>
                </div>

                <div class="relative z-10 w-full p-12 flex flex-col justify-between">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-12 h-12 bg-white rounded-full p-1.5 shadow-lg flex-shrink-0">
                            <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="w-full h-full object-contain" >
                        </div>

                        <div>
                            <p class="font-bold tracking-wide text-xl leading-none">PSARECO</p>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-white/70">
                                Polot Somagongsong Agrarian Reform Cooperative
                            </p>
                        </div>
                    </div>
                    <div class="my-auto py-8 max-w-lg">
                        <h2 class="text-3xl xl:text-4xl font-bold tracking-tight leading-tight">
                            Farm Resource Management System
                        </h2>
                        <p class="mt-4 text-sm leading-relaxed text-white/80 max-w-md">
                            Manage agricultural machinery, monitor inventory, and organize farm operations in one centralized system.
                        </p>
                        <div class="mt-8 grid grid-cols-2 gap-4 max-w-md">
                            <div class="flex items-center space-x-3 bg-white/10 border border-white/15 rounded-xl p-3.5 backdrop-blur-sm">
                                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-tractor text-emerald-200 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold leading-snug">Machinery Scheduling</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 bg-white/10 border border-white/15 rounded-xl p-3.5 backdrop-blur-sm">
                                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-boxes-stacked text-emerald-200 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold leading-snug">Inventory Monitoring</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-[11px] text-white/50">
                        <span>Centralized Agricultural Management</span>
                    </div>
                </div>
            </div>

            <div class="relative w-full lg:w-[45%] min-h-screen sm:min-h-0 lg:min-h-full flex items-center justify-center bg-white overflow-hidden">
                <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
                    <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full bg-[#d2e8d9]/50 blur-3xl"></div>
                    <div class="absolute -bottom-32 -left-32 w-72 h-72 rounded-full bg-emerald-50/70 blur-3xl"></div>
                </div>
                <div class="absolute -right-20 -bottom-28 w-[420px] h-[420px] sm:w-[500px] sm:h-[500px] opacity-[0.08] pointer-events-none transform -rotate-12 select-none z-0 lg:hidden">
                    <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="" class="w-full h-full object-contain filter grayscale text-emerald-900">
                </div>
                <div class="relative z-10 w-full max-w-md p-6 sm:p-8 lg:p-10 my-auto flex flex-col justify-between min-h-[580px] lg:min-h-0">
                    <div class="my-auto">
                        <div class="text-center mb-8">
                            <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105">
                                <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="w-20 h-auto mx-auto drop-shadow-sm" >
                            </a>
                            <h1 class="mt-4 text-3xl font-bold tracking-tight text-[#2c7a56]">PSARECO</h1>
                            <p class="text-sm font-medium text-slate-700 mt-1">Farm Resource Management System</p>
                            <div class="flex items-center justify-center space-x-2 mt-2">
                                <p class="text-xs text-slate-500 font-normal">
                                    Machinery Scheduling <span class="mx-1 text-[#3d8b68]">•</span> Inventory Monitoring
                                </p>
                            </div>
                        </div>

                        @include('components.errors')
                        @include('components.success')

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="off" placeholder=" " class="peer w-full px-4 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border
                                    @error('email') border-red-500 focus:ring-red-500 @else border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68] @enderror rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all" >

                                <label  for="email" class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none" >
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
                                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required autocomplete="current-password" placeholder=" " class="peer w-full pl-4 pr-12 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border
                                        @error('password') border-red-500 focus:ring-red-500 @else border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68] @enderror rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all" >

                                    <label for="password" class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none" >
                                        <i class="fas fa-lock text-slate-400 peer-focus:text-[#2c7a56]"></i>
                                        <span>Password</span>
                                    </label>

                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3 text-slate-400 hover:text-[#2c7a56] p-1.5 rounded-lg transition-colors focus:outline-none" >
                                        <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'" ></i>
                                    </button>
                                </div>

                                @error('password')
                                    <p class="mt-1 text-xs text-red-500 font-medium">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full mt-2 bg-[#3d8b68] hover:bg-[#327356] active:bg-[#276447] text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center space-x-2 text-sm cursor-pointer" >
                                <i class="fas fa-sign-in-alt text-xs"></i>
                                <span>Sign in to PSARECO</span>
                            </button>
                        </form>

                        <div class="text-center mt-6">
                            <p class="text-xs text-slate-500">
                                Need an account? Please visit the PSARECO office to request your login credentials.
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 text-center pt-4 border-t border-slate-100">
                        <p class="text-[11px] text-slate-400">
                            © 2026 PSARECO. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
