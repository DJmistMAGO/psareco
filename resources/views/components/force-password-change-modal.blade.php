<div
    x-data="{
        show: true,
        showCurrent: false,
        showNew: false,
        showConfirm: false,
        password: '',
        confirmation: '',

        get strength() {
            let score = 0;
            const password = this.password;

            if (!password) return 0;
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            return Math.min(score, 5);
        },

        get strengthLabel() {
            return [
                '',
                'Too short',
                'Weak',
                'Fair',
                'Good',
                'Strong'
            ][this.strength];
        },

        get strengthColor() {
            return [
                'bg-slate-200',
                'bg-red-500',
                'bg-orange-500',
                'bg-amber-500',
                'bg-emerald-500',
                'bg-green-600'
            ][this.strength];
        },

        get strengthWidth() {
            return (this.strength / 5) * 100;
        },

        get passwordsMatch() {
            return this.confirmation.length > 0 &&
                this.password === this.confirmation;
        }
    }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm"
>
    <div class="w-full max-w-md mx-4 bg-white rounded-2xl shadow-xl p-6">

        <div class="flex items-center gap-3 mb-1">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-50 text-amber-500">
                <i class="fa-solid fa-key"></i>
            </div>

            <h3 class="font-bold text-slate-800 text-base">
                Set a New Password
            </h3>
        </div>

        <p class="text-xs text-slate-500 mb-5">
            For security, you need to set your own password before continuing.
        </p>

        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Current (Temporary) Password
                </label>

                <div class="relative">
                    <input
                        :type="showCurrent ? 'text' : 'password'"
                        name="current_password"
                        required
                        class="w-full px-3 py-2 pr-10 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition {{ $errors->has('current_password') ? 'border-red-500' : 'border-slate-200' }}"
                    >

                    <button
                        type="button"
                        @click="showCurrent = !showCurrent"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition"
                        tabindex="-1"
                    >
                        <i
                            class="fa-solid"
                            :class="showCurrent ? 'fa-eye-slash' : 'fa-eye'"
                        ></i>
                    </button>
                </div>

                @error('current_password')
                    <p class="mt-1 text-red-500 text-[11px] font-medium">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    New Password
                </label>

                <div class="relative">
                    <input
                        :type="showNew ? 'text' : 'password'"
                        name="password"
                        required
                        minlength="8"
                        x-model="password"
                        class="w-full px-3 py-2 pr-10 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition {{ $errors->has('password') ? 'border-red-500' : 'border-slate-200' }}"
                    >

                    <button
                        type="button"
                        @click="showNew = !showNew"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition"
                        tabindex="-1"
                    >
                        <i
                            class="fa-solid"
                            :class="showNew ? 'fa-eye-slash' : 'fa-eye'"
                        ></i>
                    </button>
                </div>

                <div x-show="password.length > 0" x-transition class="mt-2">

                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] text-slate-400">
                            Password strength
                        </span>

                        <span
                            class="text-[10px] font-semibold"
                            :class="{
                                'text-red-500': strength === 1,
                                'text-orange-500': strength === 2,
                                'text-amber-500': strength === 3,
                                'text-emerald-500': strength === 4,
                                'text-green-600': strength === 5
                            }"
                            x-text="strengthLabel"
                        ></span>
                    </div>

                    <div class="flex gap-1 h-1">
                        <template x-for="segment in 5" :key="segment">
                            <div
                                class="flex-1 rounded-full bg-slate-200 overflow-hidden"
                            >
                                <div
                                    class="h-full rounded-full transition-all duration-300"
                                    :class="segment <= strength ? strengthColor : 'bg-transparent'"
                                ></div>
                            </div>
                        </template>
                    </div>

                    <p class="mt-1.5 text-[10px] text-slate-400">
                        Use at least 8 characters with uppercase, lowercase,
                        numbers, and symbols.
                    </p>
                </div>

                @error('password')
                    <p class="mt-1 text-red-500 text-[11px] font-medium">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Confirm New Password
                </label>

                <div class="relative">
                    <input
                        :type="showConfirm ? 'text' : 'password'"
                        name="password_confirmation"
                        required
                        minlength="8"
                        x-model="confirmation"
                        class="w-full px-3 py-2 pr-10 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition"
                        :class="{
                            'border-red-400': confirmation && !passwordsMatch,
                            'border-emerald-400': passwordsMatch
                        }"
                    >

                    <button
                        type="button"
                        @click="showConfirm = !showConfirm"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition"
                        tabindex="-1"
                    >
                        <i
                            class="fa-solid"
                            :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"
                        ></i>
                    </button>
                </div>

                {{-- Match Indicator --}}
                <div
                    x-show="confirmation.length > 0"
                    x-transition
                    class="mt-1.5 flex items-center gap-1 text-[10px] font-medium"
                    :class="passwordsMatch ? 'text-emerald-600' : 'text-red-500'"
                >
                    <i
                        class="fa-solid"
                        :class="passwordsMatch ? 'fa-circle-check' : 'fa-circle-xmark'"
                    ></i>

                    <span
                        x-text="passwordsMatch ? 'Passwords match' : 'Passwords do not match'"
                    ></span>
                </div>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 rounded-xl shadow-sm transition"
            >
                <i class="fa-solid fa-check text-[11px]"></i>
                Update Password
            </button>

        </form>
    </div>
</div>
