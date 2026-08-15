@extends('layouts.auth')

@section('title', 'Register - PSARECO')

@section('content')
<div class="min-h-screen bg-[#d2e8d9] flex items-center justify-center p-0 sm:p-4 lg:p-6 overflow-hidden">
    <div class="relative w-full max-w-6xl min-h-screen sm:min-h-0 lg:min-h-[680px] bg-white/80 backdrop-blur-sm lg:rounded-3xl shadow-2xl border border-white/70 overflow-hidden flex flex-col lg:flex-row">
        <div class="relative hidden lg:flex lg:w-[55%] overflow-hidden bg-gradient-to-br from-[#2c7a56] via-[#3d8b68] to-[#286347] text-white">
            <div class="absolute -right-20 -bottom-28 w-[420px] h-[420px] opacity-10 pointer-events-none transform -rotate-12 select-none z-0">
                <img  src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="Psareco Logo" class="w-full h-full object-contain filter brightness-200">
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
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white rounded-full p-1.5 shadow-lg">
                        <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="w-full h-full object-contain" >
                    </div>

                    <div>
                        <p class="font-bold tracking-wide text-lg">PSARECO</p>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-white/70">
                            Farm Operations
                        </p>
                    </div>
                </div>

                <div class="my-auto max-w-lg pt-8">
                    <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 backdrop-blur-sm mb-5">
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-white/80">
                            Farmer Cooperative Portal
                        </span>
                    </div>

                    <h2 class="text-4xl xl:text-5xl font-bold tracking-tight leading-tight">
                        Join our community.
                        <span class="text-emerald-200">Grow together.</span>
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-white/70 max-w-md">
                        Register as a cooperative member to request machinery schedules, manage inventory resources, and streamline your farming workflow.
                    </p>

                    <div class="mt-4 grid grid-cols-2 gap-3 max-w-md">

                        <div class="flex items-center space-x-3 bg-white/10 border border-white/10 rounded-xl px-4 py-3 backdrop-blur-sm">
                            <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fas fa-tractor text-emerald-200"></i>
                            </div>

                            <div>
                                <p class="text-xs font-bold">Machinery</p>
                                <p class="text-[10px] text-white/60">Scheduling</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 bg-white/10 border border-white/10 rounded-xl px-4 py-3 backdrop-blur-sm">
                            <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fas fa-boxes-stacked text-emerald-200"></i>
                            </div>

                            <div>
                                <p class="text-xs font-bold">Inventory</p>
                                <p class="text-[10px] text-white/60">Monitoring</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex items-center justify-between text-[10px] text-white/50 pt-6">
                    <span class="flex items-center space-x-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                        <span>Polot Somagongsong Agrarian Reform Cooperative - PSARECO</span>
                    </span>
                </div>

            </div>
        </div>

        <div class="relative w-full lg:w-[45%] min-h-screen sm:min-h-0 lg:min-h-full flex items-center justify-center bg-white overflow-hidden py-6">

            <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
                <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full bg-[#d2e8d9]/50 blur-3xl"></div>
                <div class="absolute -bottom-32 -left-32 w-72 h-72 rounded-full bg-emerald-50/70 blur-3xl"></div>
            </div>

            <div class="absolute -right-20 -bottom-28 w-[420px] h-[420px] sm:w-[500px] sm:h-[500px] opacity-[0.08] pointer-events-none transform -rotate-12 select-none z-0 lg:hidden">
                <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="" class="w-full h-full object-contain filter grayscale text-emerald-900" >
            </div>

            <div class="relative z-10 w-full max-w-md p-6 sm:p-8 lg:p-4 my-auto">

                <div class="text-center mb-6">

                    <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105">
                        <div class="relative">
                            <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="w-20 h-auto mx-auto drop-shadow-sm" >
                        </div>
                    </a>

                    <h1 class="mt-3 mb-1 text-3xl font-bold tracking-tight text-[#2c7a56]">
                        Create Account
                    </h1>

                    <p class="text-xs font-bold text-slate-600 mt-1 uppercase tracking-wide">
                        Register as Farmer Member
                    </p>

                    <div class="flex items-center justify-center space-x-2 mt-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3d8b68]"></span>
                        <p class="text-[11px] text-slate-400">Machinery Scheduling</p>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3d8b68]"></span>
                        <p class="text-[11px] text-slate-400">Inventory Monitoring</p>
                    </div>

                </div>

                <div class="flex items-center justify-center mb-6">
                    <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-100">
                        <span class="relative flex h-4 w-4 items-center justify-center text-emerald-600">
                            <i class="fas fa-shield text-sm animate-ping absolute"></i>
                            <i class="fas fa-shield text-sm relative"></i>
                        </span>
                        <span class="text-[10px] font-semibold text-emerald-700">
                            Secure Cooperative Registration
                        </span>
                    </div>
                </div>

                @include('components.errors')
                @include('components.success')

                <form method="POST" action="{{ route('register') }}"
                        {{-- PASSWORD STRENGTH --}}
                        x-data="{
                            password: '',
                            password_confirmation: '',
                            showPassword: false,
                            showConfirmPassword: false,
                            get strength() {
                                let score = 0;
                                if (!this.password) return { width: '0%', text: '', color: '' };
                                if (this.password.length >= 6) score += 20;
                                if (this.password.length >= 8) score += 20;
                                if (this.password.length >= 12) score += 10;
                                if (/[A-Z]/.test(this.password)) score += 15;
                                if (/[a-z]/.test(this.password)) score += 15;
                                if (/[0-9]/.test(this.password)) score += 10;
                                if (/[^A-Za-z0-9]/.test(this.password)) score += 10;

                                if (score < 30) return { width: Math.min(score, 100) + '%', text: '🔴 Weak', color: 'bg-red-500 text-red-600' };
                                if (score < 60) return { width: Math.min(score, 100) + '%', text: '🟡 Fair', color: 'bg-amber-400 text-amber-500' };
                                if (score < 80) return { width: Math.min(score, 100) + '%', text: '🔵 Good', color: 'bg-sky-500 text-sky-600' };
                                return { width: '100%', text: '🟢 Strong', color: 'bg-emerald-500 text-emerald-600' };
                            }
                        }"
                        class="space-y-4">
                    @csrf

                    <div class="relative">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name" placeholder=" " class="peer w-full px-4 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border
                            @error('name')
                                border-red-500 focus:ring-red-500
                            @else
                                border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68]
                            @enderror
                            rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all"
                        >

                        <label for="name" class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none" >
                            <i class="fas fa-user text-slate-400 peer-focus:text-[#2c7a56]"></i>
                            <span>Full Name</span>
                        </label>

                        @error('name')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="off" placeholder=" " class="peer w-full px-4 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border
                            @error('email')
                                border-red-500 focus:ring-red-500
                            @else
                                border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68]
                            @enderror
                            rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all"
                        >

                        <label for="email" class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none" >
                            <i class="fas fa-envelope text-slate-400 peer-focus:text-[#2c7a56]"></i>
                            <span>Email Address</span>
                        </label>

                        @error('email')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <div class="relative flex items-center">
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="password" x-model="password" required autocomplete="new-password" placeholder=" " class="peer w-full pl-4 pr-12 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border
                                @error('password')
                                    border-red-500 focus:ring-red-500
                                @else
                                    border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68]
                                @enderror
                                rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all"
                            >

                            <label for="password" class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none" >
                                <i class="fas fa-lock text-slate-400 peer-focus:text-[#2c7a56]"></i>
                                <span>Password</span>
                            </label>

                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 text-slate-400 hover:text-[#2c7a56] p-1.5 rounded-lg transition-colors focus:outline-none"
                            >
                                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>

                        <small class="text-[10px] text-slate-400 block ml-1">Minimum 6 characters</small>

                        <div x-show="password.length > 0" class="mt-2 space-y-1" x-cloak>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-full transition-all duration-300" :class="strength.color.split(' ')[0]" :style="`width: ${strength.width}`"></div>
                            </div>
                            <span class="text-[11px] font-semibold block" :class="strength.color.split(' ')[1]" x-text="strength.text"></span>
                        </div>

                        @error('password')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative flex items-center">
                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" id="confirmPassword" x-model="password_confirmation" required autocomplete="new-password" placeholder=" " class="peer w-full pl-4 pr-12 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border
                            @error('password_confirmation')
                                border-red-500 focus:ring-red-500
                            @else
                                border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68]
                            @enderror
                            rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all"
                        >

                        <label for="confirmPassword" class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none" >
                            <i class="fas fa-check-circle text-slate-400 peer-focus:text-[#2c7a56]"></i>
                            <span>Confirm Password</span>
                        </label>

                        <button
                            type="button"
                            @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute right-3 text-slate-400 hover:text-[#2c7a56] p-1.5 rounded-lg transition-colors focus:outline-none"
                        >
                            <i class="fas" :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full mt-2 bg-[#3d8b68] hover:bg-[#327356] active:bg-[#276447] text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center space-x-2 text-sm" >
                        <i class="fas fa-user-plus text-xs"></i>
                        <span>Register Account</span>
                    </button>

                </form>

                <div class="text-center mt-6">
                    <p class="text-xs text-slate-500">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-[#2c7a56] font-bold hover:underline" >
                            Login here
                        </a>
                    </p>
                </div>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-100"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-3 bg-white text-[9px] text-slate-300 uppercase tracking-widest">
                            Polot Somagongsong Agrarian Reform Cooperative
                        </span>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-[10px] text-slate-300 mt-2">
                        © 2026 PSARECO. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
