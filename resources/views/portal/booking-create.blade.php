<x-penyewa-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3">
                    
                    <div class="bg-gray-50 p-8 border-r border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Unit</h3>
                        
                        <div class="w-full h-40 bg-gray-200 rounded-lg overflow-hidden mb-4 relative group">
                            @if($unit->gambar)
                                <img src="{{ asset('storage/' . $unit->gambar) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>

                        <h4 class="text-xl font-extrabold text-indigo-700">{{ $unit->name }}</h4>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            Rp {{ number_format($unit->price, 0, ',', '.') }}
                            <span class="text-sm font-normal text-gray-500">/ bulan</span>
                        </p>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500">Deskripsi Singkat:</p>
                            <p class="text-sm text-gray-700 mt-1 line-clamp-4">{{ $unit->description ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-8">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Isi Data Booking</h3>
                            <p class="text-sm text-gray-500">Tentukan kapan Anda berencana mulai masuk.</p>
                        </div>

                        <form action="{{ route('penyewa.booking.store', $unit->id) }}" method="POST">
                            @csrf

                            <div class="mb-6">
                                <label class="block font-medium text-sm text-gray-700 mb-2">Rencana Tanggal Masuk</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="date" name="tanggal_mulai" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5" required min="{{ date('Y-m-d') }}">
                                </div>
                                <p class="text-xs text-gray-500 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Pilih tanggal hari ini jika ingin langsung aktif.
                                </p>
                            </div>

                            <div class="mb-8">
                                <label class="block font-medium text-sm text-gray-700 mb-2">Rencana Durasi (Bulan)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <input type="number" name="durasi" value="1" min="1" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5" required>
                                </div>
                            </div>

                            <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ url()->previous() }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition">
                                    Batal
                                </a>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold text-sm shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center">
                                    Kirim Pengajuan
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-penyewa-layout>