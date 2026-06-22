<x-app-layout>
    <x-slot name="title">Tambah User Baru</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition duration-150 dark:hover:bg-gray-800 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Tambah User Baru</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Daftarkan akun pengguna baru beserta rolenya.</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800" x-data="{ role: '{{ old('role', 'pcl') }}' }">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Rendi Efandi" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" required>
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Contoh: rendi@semon.id atau rendi@bps.go.id" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" required>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Password</label>
                    <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" required>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Role Akses</label>
                    <select name="role" id="role" x-model="role" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" required>
                        <option value="pcl">PCL (Petugas Lapangan / PPL)</option>
                        <option value="pml">PML (Pemeriksa / Supervisor)</option>
                        <option value="admin">Admin</option>
                        <option value="provinsi">Provinsi (Hanya View Dashboard)</option>
                    </select>
                    @error('role')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ID Petugas (Visible if PCL/PML) -->
                <div x-show="role === 'pcl' || role === 'pml'" x-cloak class="p-4 bg-orange-50/50 dark:bg-gray-800/30 border border-orange-100 dark:border-gray-800 rounded-2xl space-y-4">
                    <div>
                        <label for="id_petugas" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">ID Petugas (Angka Kustom, Misal NIP/ID)</label>
                        <input type="number" name="id_petugas" id="id_petugas" value="{{ old('id_petugas') }}" placeholder="Contoh: 2102001" class="w-full px-4 py-2.5 text-sm bg-white border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" :required="role === 'pcl' || role === 'pml'">
                        @error('id_petugas')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PML Supervisor Dropdown (Visible if PCL) -->
                    <div x-show="role === 'pcl'" x-cloak>
                        <label for="pml_id" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">PML Pengawas / Supervisor</label>
                        <select name="pml_id" id="pml_id" class="w-full px-4 py-2.5 text-sm bg-white border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" :required="role === 'pcl'">
                            <option value="">Pilih PML Pengawas...</option>
                            @foreach($pmls as $pml)
                                <option value="{{ $pml->id }}" {{ old('pml_id') == $pml->id ? 'selected' : '' }}>{{ $pml->nama }} (ID: {{ $pml->id }})</option>
                            @endforeach
                        </select>
                        @error('pml_id')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-750 bg-gray-100 hover:bg-gray-200 rounded-xl transition duration-150 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-bps-600 hover:bg-bps-700 focus:ring-4 focus:ring-bps-300 rounded-xl dark:bg-bps-500 dark:hover:bg-bps-600 transition duration-150">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
