@extends('layouts.auth')

@section('title', 'Register - PSARECO')

@section('content')
<div class="min-h-screen bg-[#d2e8d9] flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-emerald-100 p-6 sm:p-8 transition-all">

        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="w-20 h-auto mx-auto drop-shadow-sm">
            </a>
            <h3 class="mt-3 text-2xl font-bold tracking-tight text-[#2c7a56]">Create Account</h3>
            <p class="text-xs font-semibold text-slate-500 mt-1">Register as Farmer Member</p>
        </div>

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="mb-5 bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 text-red-800 shadow-sm relative">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-sm"></i>
                        <div>
                            <h4 class="font-bold text-sm">Registration Error</h4>
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

        <form method="POST" action="{{ route('register') }}"
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
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder=" "
                       class="peer w-full px-4 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border @error('name') border-red-500 focus:ring-red-500 @else border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68] @enderror rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all">

                <label for="name"
                       class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none">
                    <i class="fas fa-user text-slate-400 peer-focus:text-[#2c7a56]"></i>
                    <span>Full Name</span>
                </label>

                @error('name')
                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="relative">
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="off" placeholder=" "
                       class="peer w-full px-4 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border @error('email') border-red-500 focus:ring-red-500 @else border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68] @enderror rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all">

                <label for="email"
                       class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none">
                    <i class="fas fa-envelope text-slate-400 peer-focus:text-[#2c7a56]"></i>
                    <span>Email Address</span>
                </label>

                @error('email')
                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="relative flex items-center">
                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password" x-model="password" required autocomplete="new-password" placeholder=" "
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

                <small class="text-[11px] text-slate-400 block mt-1 ml-1">Minimum 6 characters</small>

                <div x-show="password.length > 0" class="mt-2 space-y-1" x-cloak>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="h-full transition-all duration-300"
                             :class="strength.color.split(' ')[0]"
                             :style="`width: ${strength.width}`"></div>
                    </div>
                    <span class="text-[11px] font-semibold block" :class="strength.color.split(' ')[1]" x-text="strength.text"></span>
                </div>

                @error('password')
                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="relative flex items-center">
                    <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" id="confirmPassword" x-model="password_confirmation" required autocomplete="new-password" placeholder=" "
                           class="peer w-full pl-4 pr-12 pt-5 pb-2 text-sm text-slate-800 bg-slate-50 border @error('password_confirmation') border-red-500 focus:ring-red-500 @else border-slate-200 focus:ring-[#3d8b68] focus:border-[#3d8b68] @enderror rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-all">

                    <label for="confirmPassword"
                           class="absolute left-4 top-2 text-[11px] font-medium text-slate-400 transition-all peer-placeholder-shown:text-xs peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-slate-400 peer-focus:top-1.5 peer-focus:text-[10px] peer-focus:text-[#2c7a56] flex items-center space-x-1.5 pointer-events-none">
                        <i class="fas fa-check-circle text-slate-400 peer-focus:text-[#2c7a56]"></i>
                        <span>Confirm Password</span>
                    </label>

                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute right-3 text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors focus:outline-none">
                        <i class="fas" :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>

                <template x-if="password && password_confirmation">
                    <p class="text-xs font-medium mt-1 ml-1"
                       :class="password === password_confirmation ? 'text-emerald-600' : 'text-red-500'"
                       x-text="password === password_confirmation ? '✓ Passwords match' : '✗ Passwords do not match'">
                    </p>
                </template>

                @error('password_confirmation')
                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r-xl text-amber-900 text-xs space-y-1">
                <div class="flex items-center space-x-1.5 font-bold text-amber-800">
                    <i class="fas fa-clock"></i>
                    <span>Registration requires approval!</span>
                </div>
                <p class="text-[11px] text-amber-700 leading-snug">After registration, an administrator needs to approve your account before you can log in.</p>
            </div>

            <button type="submit"
                    class="w-full bg-[#3d8b68] hover:bg-[#327356] active:bg-[#276447] text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 text-sm">
                <i class="fas fa-user-plus text-xs"></i>
                <span>Register as Farmer</span>
            </button>
        </form>

        <div class="text-center mt-5">
            <p class="text-xs text-slate-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-[#2c7a56] font-bold hover:underline">Login</a>
            </p>
        </div>

        <div class="relative my-5">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
        </div>

        <div class="text-center">
            <p class="text-[11px] text-slate-400 italic flex items-center justify-center space-x-1">
                <i class="fas fa-copyright text-[10px]"></i>
                <span>2026 PSARECO Cooperative</span>
            </p>
        </div>

    </div>
</div>
@endsection
