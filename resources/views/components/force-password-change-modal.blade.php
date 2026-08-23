<div
    x-data="{ show: true }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm"
>
    <div class="w-full max-w-md mx-4 bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center gap-3 mb-1">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-50 text-amber-500">
                <i class="fa-solid fa-key"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-base">Set a New Password</h3>
        </div>
        <p class="text-xs text-slate-500 mb-5">
            For security, you need to set your own password before continuing.
        </p>

        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Current (Temporary) Password</label>
                <input type="password" name="current_password" required
                    class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition {{ $errors->has('current_password') ? 'border-red-500' : 'border-slate-200' }}">
                @error('current_password')
                    <p class="mt-1 text-red-500 text-[11px] font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">New Password</label>
                <input type="password" name="password" required minlength="8"
                    class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition {{ $errors->has('password') ? 'border-red-500' : 'border-slate-200' }}">
                @error('password')
                    <p class="mt-1 text-red-500 text-[11px] font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required minlength="8"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
            </div>

            <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 rounded-xl shadow-sm transition">
                <i class="fa-solid fa-check text-[11px]"></i> Update Password
            </button>
        </form>
    </div>
</div>
