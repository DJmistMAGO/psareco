<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSARECO - Farm Resource Management System</title>

    <!-- Tailwind CSS CDN (For quick setup / integration with Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'farm-green': {
                            700: '#1e4d2b',
                            800: '#163e22',
                            900: '#0f2b18',
                        },
                        'farm-leaf': '#2e7d32',
                        'farm-light': '#f4f7f4',
                        'farm-earth': '#8d6e63',
                        'farm-gold': '#f59e0b',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-farm-light text-slate-800 font-sans antialiased">

    <!-- Header / Navigation -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-emerald-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{-- route('home') --}}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 bg-farm-green-800 rounded-xl flex items-center justify-center text-emerald-400 font-bold shadow-md group-hover:bg-farm-leaf transition-colors">
                    <i data-lucide="sprout" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-2xl font-black tracking-tight text-farm-green-900 block leading-tight">PSARECO</span>
                    <span class="text-[10px] font-semibold tracking-wider text-emerald-700 uppercase block">Farm Resource Management</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 font-medium text-slate-700 text-base">
                <a href="#home" class="text-farm-leaf font-semibold border-b-2 border-farm-leaf pb-1">Home</a>
                <a href="#machinery" class="hover:text-farm-leaf transition-colors">Machinery</a>
                <a href="#inventory" class="hover:text-farm-leaf transition-colors">Inventory</a>
                <a href="#how-it-works" class="hover:text-farm-leaf transition-colors">How It Works</a>
                <a href="#about" class="hover:text-farm-leaf transition-colors">About PSARECO</a>
            </nav>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{-- route('login') --}}" class="px-5 py-2.5 rounded-xl border-2 border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition-colors text-sm sm:text-base">
                    Login
                </a>
                <a href="{{-- route('register') --}}" class="px-6 py-2.5 rounded-xl bg-farm-green-800 text-white font-bold hover:bg-farm-leaf transition-all shadow-md hover:shadow-lg text-sm sm:text-base flex items-center gap-2">
                    <span>Get Started</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="relative py-12 lg:py-20 overflow-hidden bg-gradient-to-b from-emerald-50/50 to-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 items-center">

                <!-- Left Hero Content -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100 text-farm-green-800 font-bold text-xs sm:text-sm tracking-wide uppercase">
                        <i data-lucide="tractor" class="w-4 h-4 text-farm-leaf"></i>
                        Farm Resource Management System
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-tight">
                        Smarter Farm Resources. <br>
                        <span class="text-farm-leaf">Better Harvests.</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-slate-600 leading-relaxed font-normal">
                        Schedule agricultural machinery, monitor farm resources, and keep track of essential supplies—all in one simple platform built for farmers.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#machinery" class="px-8 py-4 bg-farm-green-800 text-white font-bold text-lg rounded-2xl shadow-lg hover:bg-farm-leaf transition-all flex items-center justify-center gap-3">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                            Schedule Machinery
                        </a>
                        <a href="#inventory" class="px-8 py-4 bg-white text-slate-800 border-2 border-slate-200 font-bold text-lg rounded-2xl hover:bg-slate-50 transition-all flex items-center justify-center gap-3 shadow-sm">
                            <i data-lucide="boxes" class="w-5 h-5 text-farm-earth"></i>
                            Explore Resources
                        </a>
                    </div>
                </div>

                <!-- Right Hero Visual -->
                <div class="lg:col-span-6 relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white aspect-[4/3]">
                        <img src="https://images.unsplash.com/photo-1592982537447-7440770cbfc9?q=80&w=1200&auto=format&fit=crop"
                             alt="Filipino farmer with tractor in rice field"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    </div>

                    <!-- Floating Card 1: Machinery Availability -->
                    <div class="absolute -bottom-4 left-4 sm:left-8 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-emerald-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                            <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Machinery Availability</p>
                            <p class="text-base font-bold text-slate-900">✓ Tractor Available</p>
                        </div>
                    </div>

                    <!-- Floating Card 2: Upcoming Schedule -->
                    <div class="absolute -top-4 right-4 sm:right-8 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-emerald-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                            <i data-lucide="clock" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Upcoming Schedule</p>
                            <p class="text-base font-bold text-slate-900">🚜 Aug 12 • 8:00 AM</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Quick Access Section -->
    <section class="py-12 bg-white border-y border-emerald-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Quick Card 1 -->
                <div class="p-6 rounded-3xl bg-emerald-50/60 border border-emerald-100 hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 bg-emerald-700 text-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                            <i data-lucide="tractor" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Machinery Scheduling</h3>
                        <p class="text-slate-600 mb-6 text-sm">Reserve agricultural machinery when you need it.</p>
                    </div>
                    <a href="#machinery" class="w-full py-3 bg-white text-emerald-800 font-bold rounded-xl text-center border border-emerald-200 hover:bg-emerald-700 hover:text-white transition-all text-sm flex items-center justify-center gap-2">
                        View Machinery
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- Quick Card 2 -->
                <div class="p-6 rounded-3xl bg-amber-50/60 border border-amber-100 hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 bg-amber-600 text-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                            <i data-lucide="package" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Inventory Monitoring</h3>
                        <p class="text-slate-600 mb-6 text-sm">Keep track of available farm supplies and resources.</p>
                    </div>
                    <a href="#inventory" class="w-full py-3 bg-white text-amber-900 font-bold rounded-xl text-center border border-amber-200 hover:bg-amber-600 hover:text-white transition-all text-sm flex items-center justify-center gap-2">
                        Check Inventory
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- Quick Card 3 -->
                <div class="p-6 rounded-3xl bg-blue-50/60 border border-blue-100 hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 bg-blue-700 text-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                            <i data-lucide="calendar-days" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">My Schedules</h3>
                        <p class="text-slate-600 mb-6 text-sm">View upcoming machinery reservations and requests.</p>
                    </div>
                    <a href="{{-- route('login') --}}" class="w-full py-3 bg-white text-blue-800 font-bold rounded-xl text-center border border-blue-200 hover:bg-blue-700 hover:text-white transition-all text-sm flex items-center justify-center gap-2">
                        View Schedule
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- Quick Card 4 -->
                <div class="p-6 rounded-3xl bg-purple-50/60 border border-purple-100 hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 bg-purple-700 text-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                            <i data-lucide="bell" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Notifications</h3>
                        <p class="text-slate-600 mb-6 text-sm">Stay updated about reservations and announcements.</p>
                    </div>
                    <a href="{{-- route('login') --}}" class="w-full py-3 bg-white text-purple-800 font-bold rounded-xl text-center border border-purple-200 hover:bg-purple-700 hover:text-white transition-all text-sm flex items-center justify-center gap-2">
                        View Updates
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-4">Getting Started Is Easy</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto mb-16">Three simple steps to access farm equipment and streamline your farming schedule.</p>

            <div class="grid md:grid-cols-3 gap-8 relative">
                <!-- Step 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 flex flex-col items-center relative">
                    <div class="w-16 h-16 bg-emerald-100 text-farm-green-800 font-black text-2xl rounded-2xl flex items-center justify-center mb-6">
                        1
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Register</h3>
                    <p class="text-slate-600 leading-relaxed">Create your farmer account and provide the required information.</p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 flex flex-col items-center relative">
                    <div class="w-16 h-16 bg-emerald-100 text-farm-green-800 font-black text-2xl rounded-2xl flex items-center justify-center mb-6">
                        2
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Schedule</h3>
                    <p class="text-slate-600 leading-relaxed">Find available machinery and submit a schedule request based on your farming needs.</p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 flex flex-col items-center relative">
                    <div class="w-16 h-16 bg-emerald-100 text-farm-green-800 font-black text-2xl rounded-2xl flex items-center justify-center mb-6">
                        3
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Farm</h3>
                    <p class="text-slate-600 leading-relaxed">Use the scheduled machinery and keep track of your resources through the platform.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Machinery Section -->
    <section id="machinery" class="py-16 sm:py-24 bg-white border-y border-emerald-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-3">The Machinery You Need, When You Need It</h2>
                    <p class="text-lg text-slate-600 max-w-2xl">Find available agricultural machinery and schedule equipment according to your farming activities.</p>
                </div>
                <a href="{{-- route('login') --}}" class="mt-4 md:mt-0 font-bold text-farm-leaf hover:underline flex items-center gap-2">
                    View All Machinery →
                </a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card 1: Tractor -->
                <div class="bg-farm-light rounded-3xl overflow-hidden border border-slate-200 shadow-sm flex flex-col">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1530267981375-f0de937f5f13?q=80&w=800&auto=format&fit=crop" alt="Farm Tractor" class="w-full h-full object-cover">
                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full bg-emerald-500 text-white text-xs font-bold flex items-center gap-1.5 shadow">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> Available
                        </span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Farm Tractor</h3>
                            <div class="space-y-1 text-sm text-slate-600 mb-6">
                                <p><span class="font-semibold">Capacity:</span> 50 HP</p>
                                <p><span class="font-semibold">Type:</span> Multi-Purpose Heavy Duty</p>
                            </div>
                        </div>
                        <a href="{{-- route('login') --}}" class="w-full py-3 bg-farm-green-800 text-white font-bold text-center rounded-xl hover:bg-farm-leaf transition-colors">
                            Schedule
                        </a>
                    </div>
                </div>

                <!-- Card 2: Rice Transplanter -->
                <div class="bg-farm-light rounded-3xl overflow-hidden border border-slate-200 shadow-sm flex flex-col">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1586771107445-d3ca888129ff?q=80&w=800&auto=format&fit=crop" alt="Rice Transplanter" class="w-full h-full object-cover">
                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full bg-emerald-500 text-white text-xs font-bold flex items-center gap-1.5 shadow">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> Available
                        </span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Rice Transplanter</h3>
                            <div class="space-y-1 text-sm text-slate-600 mb-6">
                                <p><span class="font-semibold">Capacity:</span> 6-Row Planting</p>
                                <p><span class="font-semibold">Type:</span> Walk-Behind High Efficiency</p>
                            </div>
                        </div>
                        <a href="{{-- route('login') --}}" class="w-full py-3 bg-farm-green-800 text-white font-bold text-center rounded-xl hover:bg-farm-leaf transition-colors">
                            Schedule
                        </a>
                    </div>
                </div>

                <!-- Card 3: Harvester -->
                <div class="bg-farm-light rounded-3xl overflow-hidden border border-slate-200 shadow-sm flex flex-col">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1595838729984-331123722160?q=80&w=800&auto=format&fit=crop" alt="Rice Combine Harvester" class="w-full h-full object-cover">
                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center gap-1.5 shadow">
                            Scheduled
                        </span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Combine Harvester</h3>
                            <div class="space-y-1 text-sm text-slate-600 mb-6">
                                <p><span class="font-semibold">Capacity:</span> High output</p>
                                <p><span class="font-semibold">Type:</span> Rice & Corn Harvester</p>
                            </div>
                        </div>
                        <a href="{{-- route('login') --}}" class="w-full py-3 bg-slate-200 text-slate-700 font-bold text-center rounded-xl hover:bg-slate-300 transition-colors">
                            Check Availability
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Inventory Monitoring Section -->
    <section id="inventory" class="py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 items-center">

                <!-- Left Visual Image -->
                <div class="lg:col-span-5">
                    <div class="rounded-3xl overflow-hidden shadow-xl border-4 border-white">
                        <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=800&auto=format&fit=crop"
                             alt="Agricultural supplies inventory"
                             class="w-full h-96 object-cover">
                    </div>
                </div>

                <!-- Right Content & Table -->
                <div class="lg:col-span-7 space-y-6">
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900">Know What Resources Are Available</h2>
                    <p class="text-lg text-slate-600">Monitor essential farm resources and supplies so you can plan your farming activities with confidence.</p>

                    <!-- Inventory Table Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-emerald-50/70 border-b border-emerald-100 text-xs font-bold text-emerald-900 uppercase tracking-wider">
                                    <th class="py-3.5 px-6">Resource</th>
                                    <th class="py-3.5 px-6">Available</th>
                                    <th class="py-3.5 px-6 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                <tr>
                                    <td class="py-4 px-6 font-semibold text-slate-900">Rice Seeds</td>
                                    <td class="py-4 px-6">1,250 kg</td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            🟢 Available
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-semibold text-slate-900">Fertilizer</td>
                                    <td class="py-4 px-6">580 bags</td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                            🟡 Low Stock
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-semibold text-slate-900">Diesel</td>
                                    <td class="py-4 px-6">820 L</td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            🟢 Available
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-semibold text-slate-900">Spare Parts</td>
                                    <td class="py-4 px-6">42 items</td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            🟢 Available
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <a href="{{-- route('login') --}}" class="inline-flex items-center gap-2 px-6 py-3 bg-farm-green-800 text-white font-bold rounded-xl hover:bg-farm-leaf transition-colors">
                            View Full Inventory
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Farmer Dashboard Preview Section -->
    <section class="py-16 sm:py-24 bg-white border-t border-emerald-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-4">Everything You Need in One Place</h2>
                <p class="text-lg text-slate-600">A clean, farmer-friendly dashboard design that works seamlessly across desktop and mobile devices.</p>
            </div>

            <!-- UI Mockup Window -->
            <div class="max-w-4xl mx-auto bg-farm-light rounded-3xl border-2 border-slate-200 shadow-2xl p-6 sm:p-8">
                <!-- Top Header Mock -->
                <div class="flex items-center justify-between pb-6 border-b border-slate-200">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Welcome back, Farmer Juan! 👋</h3>
                        <p class="text-sm text-slate-500">Here is your farm resource overview for today.</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold">Active Account</span>
                </div>

                <!-- Dashboard Content Cards Grid -->
                <div class="grid sm:grid-cols-3 gap-6 mt-6">
                    <!-- Dashboard Card 1 -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <span class="p-2 bg-emerald-100 text-emerald-700 rounded-xl">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </span>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">Approved</span>
                        </div>
                        <h4 class="font-bold text-slate-900">Upcoming Schedule</h4>
                        <p class="text-sm font-semibold text-farm-leaf mt-1">🚜 Farm Tractor</p>
                        <p class="text-xs text-slate-500">August 12, 2026 • 8:00 AM</p>
                    </div>

                    <!-- Dashboard Card 2 -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <span class="p-2 bg-amber-100 text-amber-700 rounded-xl">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </span>
                            <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">Pending</span>
                        </div>
                        <h4 class="font-bold text-slate-900">Machinery Request</h4>
                        <p class="text-sm font-semibold text-amber-700 mt-1">🌾 Rice Harvester</p>
                        <p class="text-xs text-slate-500">Submitted yesterday</p>
                    </div>

                    <!-- Dashboard Card 3 -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <span class="p-2 bg-red-100 text-red-700 rounded-xl">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            </span>
                            <span class="text-xs font-bold text-red-700 bg-red-50 px-2 py-0.5 rounded-md">Alert</span>
                        </div>
                        <h4 class="font-bold text-slate-900">Inventory Alert</h4>
                        <p class="text-sm font-semibold text-red-600 mt-1">Fertilizer (Low Stock)</p>
                        <p class="text-xs text-slate-500">580 bags remaining</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Farmer-Focused Benefits Section -->
    <section class="py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-4">Built Around the Needs of Farmers</h2>
                <p class="text-lg text-slate-600">Designed to make farm equipment access clear, accessible, and dependable.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 text-center">
                    <div class="text-4xl mb-4">🌾</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Plan Better</h3>
                    <p class="text-slate-600 text-sm">Schedule machinery according to your farming activities.</p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200 text-center">
                    <div class="text-4xl mb-4">🚜</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Access Resources</h3>
                    <p class="text-slate-600 text-sm">Know which agricultural equipment is available in real-time.</p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200 text-center">
                    <div class="text-4xl mb-4">📦</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Monitor Supplies</h3>
                    <p class="text-slate-600 text-sm">Keep track of essential farm resources and inventories.</p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200 text-center">
                    <div class="text-4xl mb-4">📱</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Stay Updated</h3>
                    <p class="text-slate-600 text-sm">Receive important updates about requests and schedule approvals.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust / PSARECO Section -->
    <section id="about" class="py-16 sm:py-24 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900">Technology Supporting Local Agriculture</h2>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        PSARECO's Farm Resource Management System helps connect farmers with agricultural resources through a simpler, more organized, and accessible digital platform.
                    </p>
                    <a href="{{-- route('register') --}}" class="inline-flex items-center gap-2 px-6 py-3 bg-farm-green-800 text-white font-bold rounded-xl hover:bg-farm-leaf transition-colors">
                        Learn More About PSARECO
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
                <div class="lg:col-span-6">
                    <div class="rounded-3xl overflow-hidden shadow-xl border-4 border-white">
                        <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?q=80&w=800&auto=format&fit=crop"
                             alt="Philippine agricultural landscape"
                             class="w-full h-80 object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom Call-to-Action Section -->
    <section class="py-16 sm:py-20 bg-farm-green-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop" alt="Rice field background" class="w-full h-full object-cover">
        </div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black">Ready to Plan Your Next Farm Activity?</h2>
            <p class="text-lg sm:text-xl text-emerald-100 max-w-2xl mx-auto">
                Schedule machinery, monitor resources, and manage your farm needs with ease.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="{{-- route('register') --}}" class="w-full sm:w-auto px-8 py-4 bg-white text-farm-green-900 font-bold text-lg rounded-2xl hover:bg-emerald-50 transition-colors shadow-lg">
                    Get Started
                </a>
                <a href="{{-- route('login') --}}" class="w-full sm:w-auto px-8 py-4 border-2 border-white/40 text-white font-bold text-lg rounded-2xl hover:bg-white/10 transition-colors">
                    Login
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-farm-leaf rounded-xl flex items-center justify-center text-white font-bold">
                        <i data-lucide="sprout" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-xl font-black text-white block">PSARECO</span>
                        <span class="text-xs text-slate-400">Farm Resource Management System</span>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-6 text-sm font-medium">
                    <a href="#home" class="hover:text-white transition-colors">Home</a>
                    <a href="#machinery" class="hover:text-white transition-colors">Machinery</a>
                    <a href="#inventory" class="hover:text-white transition-colors">Inventory</a>
                    <a href="#how-it-works" class="hover:text-white transition-colors">How It Works</a>
                    <a href="#about" class="hover:text-white transition-colors">About</a>
                </div>
            </div>

            <div class="pt-8 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} PSARECO. All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
