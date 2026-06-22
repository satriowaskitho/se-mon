<x-app-layout>
    <x-slot name="title">Manajemen User</x-slot>

    <div class="space-y-6">
        <!-- Header Card -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen User</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Kelola data pengguna, hak akses role, serta penugasan wilayah SLS Petugas.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-bps-600 hover:bg-bps-700 focus:ring-4 focus:ring-bps-300 rounded-xl dark:bg-bps-500 dark:hover:bg-bps-600 transition duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Tambah User Baru
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Gagal!</span> {{ session('error') }}
            </div>
        @endif

        <!-- Filter Card -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Cari Nama / Email</label>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Contoh: Donny atau donny@bps.go.id" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white dark:focus:ring-bps-500 dark:focus:border-bps-500">
                </div>
                <div>
                    <label for="role" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Filter Role</label>
                    <select name="role" id="role" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white dark:focus:ring-bps-500 dark:focus:border-bps-500">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pml" {{ $role === 'pml' ? 'selected' : '' }}>PML (Supervisor)</option>
                        <option value="pcl" {{ $role === 'pcl' ? 'selected' : '' }}>PCL (Petugas/PPL)</option>
                        <option value="provinsi" {{ $role === 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-bps-600 hover:bg-bps-700 rounded-xl focus:ring-4 focus:ring-bps-300 dark:bg-bps-500 dark:hover:bg-bps-600 transition duration-150">
                        Filter
                    </button>
                    <button type="button" id="btn-reset" class="{{ (!$search && !$role) ? 'hidden' : '' }} px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition duration-150">
                        Reset
                    </button>
                </div>
            </form>
        </div>
        <div id="users-list-wrapper" class="transition-opacity duration-200">
            @include('admin.users.list')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const roleSelect = document.getElementById('role');
            const filterForm = searchInput.closest('form');
            const wrapper = document.getElementById('users-list-wrapper');
            const resetBtn = document.getElementById('btn-reset');

            function fetchUsers(url) {
                wrapper.style.opacity = '0.5';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(html => {
                    wrapper.innerHTML = html;
                    wrapper.style.opacity = '1';

                    // Maintain input focus if search was active, setting cursor to end
                    if (document.activeElement === searchInput) {
                        const len = searchInput.value.length;
                        searchInput.setSelectionRange(len, len);
                    }
                })
                .catch(err => {
                    console.error('Error fetching users:', err);
                    wrapper.style.opacity = '1';
                });
            }

            function triggerSearch() {
                const searchVal = encodeURIComponent(searchInput.value);
                const roleVal = encodeURIComponent(roleSelect.value);
                const url = `{{ route('admin.users.index') }}?search=${searchVal}&role=${roleVal}`;
                
                // Toggle Reset button visibility dynamically
                if (searchInput.value.trim() !== '' || roleSelect.value !== '') {
                    resetBtn.classList.remove('hidden');
                } else {
                    resetBtn.classList.add('hidden');
                }

                // Update history to support back/forward navigation without page reload
                window.history.pushState({ path: url }, '', url);
                fetchUsers(url);
            }

            let timeout = null;
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    triggerSearch();
                }, 300);
            });

            roleSelect.addEventListener('change', function () {
                triggerSearch();
            });

            resetBtn.addEventListener('click', function () {
                searchInput.value = '';
                roleSelect.value = '';
                resetBtn.classList.add('hidden');
                triggerSearch();
            });

            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                triggerSearch();
            });

            // Event delegation for AJAX pagination links
            wrapper.addEventListener('click', function (e) {
                const link = e.target.closest('a');
                // Ensure we only intercept actual pagination navigation links
                if (link && link.href && !link.getAttribute('href').startsWith('javascript:') && !link.closest('form') && !link.classList.contains('text-red-600') && !link.innerText.includes('Edit')) {
                    e.preventDefault();
                    window.history.pushState({ path: link.href }, '', link.href);
                    fetchUsers(link.href);
                }
            });

            // Focus on search input at end of query if there's existing search text
            if (searchInput.value.trim() !== '') {
                searchInput.focus();
                const len = searchInput.value.length;
                searchInput.setSelectionRange(len, len);
            }
        });
    </script>
</x-app-layout>
