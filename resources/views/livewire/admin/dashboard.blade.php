<x-layouts.app>
    @section('title', 'Dashboard Admin')

    <div class="p-8">
        {{-- Header Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600 mt-1">Berikut adalah ringkasan aktivitas di SiMagang.</p>
        </div>

        {{-- Contoh penambahan tombol logout di layout utama --}}
        <nav class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        {{-- Logo/Nama Aplikasi --}}
                    </div>
                    <div class="flex items-center">
                        {{-- Tombol Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Pastikan ada tag <main> di bawah navbar --}}
        <main>
            {{ $slot }}
        </main>

        {{-- Konten Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-700">Total Peserta Magang</h2>
                <p class="text-4xl font-bold mt-2 text-indigo-600">{{ $totalInterns }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-700">Total Supervisor</h2>
                <p class="text-4xl font-bold mt-2 text-indigo-600">{{ $totalSupervisors }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-700">Laporan Baru Hari Ini</h2>
                <p class="text-4xl font-bold mt-2 text-indigo-600">8</p> {{-- Ganti dengan data dinamis nanti --}}
            </div>
        </div>

        {{-- Konten lainnya bisa ditambahkan di sini --}}
        <div class="mt-8">
            {{-- Misalnya tabel atau chart --}}
        </div>
    </div>
    
</x-layouts.app>