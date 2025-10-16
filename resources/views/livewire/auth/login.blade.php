<div class="flex min-h-screen items-center justify-center">
    <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-md">
        <h1 class="text-2xl font-bold text-center">Login SiMagang</h1>

        <form wire:submit="authenticate" class="mt-6">
            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    wire:model="email"
                    type="email"
                    id="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                    autofocus
                >
                @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    wire:model="password"
                    type="password"
                    id="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                >
            </div>

            {{-- Tombol Login --}}
            <div>
                <button type="submit" class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span wire:loading.remove>
                        Login
                    </span>
                    <span wire:loading>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>