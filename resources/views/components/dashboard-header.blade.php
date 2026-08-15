<div class="lg:hidden flex items-center justify-between mb-4 bg-white/70 backdrop-blur-md p-3 rounded-xl shadow-sm border border-emerald-100">
    <div class="flex items-center gap-2">
        <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO" class="w-8 h-8 object-contain rounded-full" >
        <div>
            <p class="font-bold text-emerald-950 text-sm leading-tight"> PSARECO </p>
            <p class="text-[10px] text-slate-500"> Farm Resource System </p>
        </div>
    </div>
    <button type="button" @click="mobileOpen = true" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#3d8b68] text-white shadow-sm hover:bg-[#327356] transition" >
        <i class="fa-solid fa-bars"></i>
    </button>
</div>
