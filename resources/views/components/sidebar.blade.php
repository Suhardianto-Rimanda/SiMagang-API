<aside class="flex h-screen w-64 flex-col overflow-y-auto border-r bg-white px-5 py-8">
    <a href="#">
        <h1 class="text-xl font-bold text-indigo-600">SiMagang</h1>
    </a>

    <div class="mt-6 flex flex-1 flex-col justify-between">
        <nav class="-mx-3 space-y-6 ">
            <div class="space-y-3 ">
                <label class="px-3 text-xs font-semibold uppercase text-gray-900">analytics</label>
                <a class="flex transform items-center rounded-lg px-3 py-2 text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700" href="{{ route('admin.dashboard') }}">
                    <span class="mx-2 text-sm font-medium">Dashboard</span>
                </a>
            </div>

            <div class="space-y-3 ">
                <label class="px-3 text-xs font-semibold uppercase text-gray-900">Manajemen</label>
                <a class="flex transform items-center rounded-lg px-3 py-2 text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700" href="#">
                    <span class="mx-2 text-sm font-medium">Peserta Magang</span>
                </a>
                <a class="flex transform items-center rounded-lg px-3 py-2 text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700" href="#">
                    <span class="mx-2 text-sm font-medium">Supervisor</span>
                </a>
                 <a class="flex transform items-center rounded-lg px-3 py-2 text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700" href="#">
                    <span class="mx-2 text-sm font-medium">Laporan Aktivitas</span>
                </a>
            </div>

            <div class="space-y-3 ">
                <label class="px-3 text-xs font-semibold uppercase text-gray-900">Akun</label>
                {{-- Tombol Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full transform items-center rounded-lg px-3 py-2 text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700">
                        <span class="mx-2 text-sm font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</aside>