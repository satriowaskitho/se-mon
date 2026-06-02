<x-app-layout>
    <x-slot name="title">Input Capaian Harian</x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-bps-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Form Capaian Harian Pencacahan
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gunakan form ini untuk mencatatkan capaian realisasi usaha dan rumah tangga yang berhasil dicacah hari ini.</p>

            <form method="POST" action="{{ route('daily-reports.store') }}" class="mt-6 space-y-5">
                @csrf

                <!-- Tanggal Laporan -->
                <div>
                    <label for="report_date_display" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Tanggal Laporan (Hari Ini)</label>
                    <input type="text" id="report_date_display" class="bg-gray-50 border border-gray-350 text-gray-900 text-sm rounded-xl focus:ring-bps-500 focus:border-bps-500 block w-full p-3 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:outline-none opacity-80 cursor-not-allowed" value="{{ now()->translatedFormat('l, d F Y') }}" readonly disabled />
                    <input type="hidden" name="report_date" value="{{ now()->format('Y-m-d') }}" />
                    @error('report_date')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SubSLS Tugas -->
                <div>
                    <label for="assignment_id" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Pilih Wilayah Tugas (SubSLS)</label>
                    <select id="assignment_id" name="assignment_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-bps-500 focus:border-bps-500 block w-full p-3 dark:bg-gray-800 dark:border-gray-750 dark:placeholder-gray-400 dark:text-white dark:focus:ring-bps-500 dark:focus:border-bps-500" required>
                        <option value="" disabled selected>-- Pilih SubSLS Tugas --</option>
                        @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}" {{ old('assignment_id') == $assignment->id ? 'selected' : '' }}>
                                SubSLS {{ $assignment->subsls->idsubsls }} - {{ $assignment->subsls->sls->nmsls }} (Target Usaha: {{ $assignment->target_usaha }})
                            </option>
                        @endforeach
                    </select>
                    @error('assignment_id')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Usaha Hari Ini -->
                    <div>
                        <label for="usaha_today" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Jumlah Usaha Dicacah Hari Ini</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <input type="number" id="usaha_today" name="usaha_today" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-bps-500 focus:border-bps-500 block w-full ps-10 p-3  dark:bg-gray-800 dark:border-gray-750 dark:placeholder-gray-400 dark:text-white dark:focus:ring-bps-500 dark:focus:border-bps-500" placeholder="0" value="{{ old('usaha_today', 0) }}" required />
                        </div>
                        @error('usaha_today')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ruta Hari Ini -->
                    <div>
                        <label for="ruta_today" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Jumlah Ruta Dicacah Hari Ini</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                            <input type="number" id="ruta_today" name="ruta_today" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-bps-500 focus:border-bps-500 block w-full ps-10 p-3  dark:bg-gray-800 dark:border-gray-750 dark:placeholder-gray-400 dark:text-white dark:focus:ring-bps-500 dark:focus:border-bps-500" placeholder="0" value="{{ old('ruta_today', 0) }}" required />
                        </div>
                        @error('ruta_today')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="notes" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Catatan (Optional)</label>
                    <textarea id="notes" name="notes" rows="4" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-300 focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-800 dark:border-gray-750 dark:placeholder-gray-400 dark:text-white dark:focus:ring-bps-500 dark:focus:border-bps-500" placeholder="Tuliskan kendala lapangan atau catatan khusus jika ada...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit buttons -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 text-white bg-bps-600 hover:bg-bps-700 focus:ring-4 focus:ring-bps-300 font-semibold rounded-xl text-sm px-5 py-3 text-center dark:bg-bps-500 dark:hover:bg-bps-600 dark:focus:ring-bps-800 transition duration-150">
                        Simpan Laporan
                    </button>
                    <a href="{{ route('dashboard') }}" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-250 font-semibold rounded-xl text-sm px-5 py-3 text-center dark:bg-gray-800 dark:text-white dark:border-gray-650 dark:hover:bg-gray-700 dark:hover:border-gray-600 transition duration-150">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
