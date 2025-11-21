<x-penyewa-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Akun') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                     class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-green-700 font-medium">Password berhasil diperbarui. Silakan gunakan password baru saat login nanti.</span>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Ganti Password</h3>
                        <p class="text-sm text-gray-500">Amankan akun Anda dengan password yang kuat.</p>
                    </div>
                    <div class="p-2 bg-indigo-100 rounded-full text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>

                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('penyewa.profil.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-5">
                            <label for="current_password" class="block font-medium text-sm text-gray-700 mb-1">Password Saat Ini</label>
                            <input id="current_password" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 py-2.5" type="password" name="current_password" required autocomplete="current-password" />
                        </div>

                        <hr class="my-6 border-gray-100">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700 mb-1">Password Baru</label>
                                <input id="password" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 py-2.5" type="password" name="password" required autocomplete="new-password" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block font-medium text-sm text-gray-700 mb-1">Ulangi Password Baru</label>
                                <input id="password_confirmation" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 py-2.5" type="password" name="password_confirmation" required autocomplete="new-password" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-penyewa-layout>