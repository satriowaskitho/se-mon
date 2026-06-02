<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — SEMON SE2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-gray-50" style="font-family: 'Inter', sans-serif;">

<div class="min-h-screen flex">
    <!-- Left: Branding Panel -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-bps-900 via-bps-800 to-bps-600 relative overflow-hidden flex-col items-center justify-center p-12">
        <!-- Decorative circles -->
        <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>

        <div class="relative z-10 text-white text-center max-w-md">
            <!-- Logo Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-white/10 backdrop-blur rounded-2xl flex items-center justify-center border border-white/20">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-4xl font-extrabold mb-2 tracking-tight">SEMON</h1>
            <p class="text-bps-200 font-semibold text-lg mb-1">Sistem Monitoring Harian</p>
            <p class="text-bps-300 text-sm mb-8">Sensus Ekonomi 2026 — Kabupaten Bintan</p>

            <div class="space-y-4 text-left">
                <div class="flex items-start gap-3 bg-white/10 backdrop-blur rounded-xl p-4 border border-white/10">
                    <div class="w-8 h-8 bg-emerald-400/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">Monitoring Real-time</p>
                        <p class="text-bps-300 text-xs mt-0.5">Pantau progres pencacahan hingga level SubSLS</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 bg-white/10 backdrop-blur rounded-xl p-4 border border-white/10">
                    <div class="w-8 h-8 bg-blue-400/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">Analisis Drill-Down</p>
                        <p class="text-bps-300 text-xs mt-0.5">Dari Kecamatan hingga petugas individual</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 bg-white/10 backdrop-blur rounded-xl p-4 border border-white/10">
                    <div class="w-8 h-8 bg-purple-400/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">Ekspor Data</p>
                        <p class="text-bps-300 text-xs mt-0.5">Download laporan Excel & CSV kapan saja</p>
                    </div>
                </div>
            </div>

            <p class="text-bps-400 text-xs mt-8">© 2026 BPS Kabupaten Bintan. Hak cipta dilindungi.</p>
        </div>
    </div>

    <!-- Right: Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="flex items-center gap-3 mb-8 lg:hidden">
                <div class="w-10 h-10 bg-bps-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-extrabold text-bps-700">SEMON SE2026</span>
                    <p class="text-xs text-gray-400">BPS Kabupaten Bintan</p>
                </div>
            </div>

            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Masuk ke Sistem</h2>
            <p class="text-sm text-gray-500 mb-8">Gunakan akun yang telah diberikan oleh Admin SEMON.</p>

            <!-- Session Status -->
            @if(session('status'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input id="email" name="email" type="email"
                            class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-bps-500 focus:border-bps-500 outline-none transition {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}"
                            placeholder="nama@semon.id"
                            value="{{ old('email') }}"
                            required autofocus autocomplete="email">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" name="password" type="password"
                            class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-bps-500 focus:border-bps-500 outline-none transition {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}"
                            placeholder="••••••••"
                            required autocomplete="current-password">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input id="remember_me" name="remember" type="checkbox"
                            class="w-4 h-4 text-bps-600 border-gray-300 rounded focus:ring-bps-500">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full py-3 px-6 text-sm font-bold text-white bg-bps-600 hover:bg-bps-700 active:bg-bps-800 rounded-xl transition duration-150 focus:ring-4 focus:ring-bps-300 focus:outline-none flex items-center justify-center gap-2 shadow-lg shadow-bps-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Masuk ke SEMON
                </button>
            </form>

            <!-- Hint box -->
            <div class="mt-8 p-4 bg-bps-50 border border-bps-100 rounded-xl">
                <p class="text-xs font-semibold text-bps-700 mb-1">ℹ️ Informasi Login</p>
                <p class="text-xs text-bps-600">Format email: <strong>namaanda@semon.id</strong></p>
                <p class="text-xs text-bps-600">Password: <strong>namakecil123</strong> (tanpa spasi)</p>
                <p class="text-xs text-gray-400 mt-1">Contoh: Budi Santoso → <code>budisantoso@semon.id</code> / <code>budisantoso123</code></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
