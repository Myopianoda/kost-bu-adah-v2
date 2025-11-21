<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                     class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-green-700 font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-400 hover:text-green-600">X</button>
                </div>
            @endif
            
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                     class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="text-red-700 font-medium">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-600">X</button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Tagihan Bulanan</h3>
                        <p class="text-sm text-gray-500">Pantau status pembayaran dan verifikasi bukti transfer.</p>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penyewa</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total Tagihan</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($semua_tagihan as $tagihan)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $tagihan->sewa->penyewa->nama_lengkap }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                            {{ $tagihan->sewa->unit->name }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-700">
                                            Rp {{ number_format($tagihan->jumlah) }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $jatuhTempo = \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo);
                                                $isOverdue = $jatuhTempo->isPast() && $tagihan->status == 'belum_bayar';
                                            @endphp
                                            <span class="{{ $isOverdue ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                                {{ $jatuhTempo->format('d M Y') }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($tagihan->status == 'lunas')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    Lunas
                                                </span>
                                            @elseif($tagihan->bukti_bayar)
                                                <div class="flex flex-col items-start gap-1">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                        Verifikasi
                                                    </span>
                                                    <a href="{{ asset('storage/' . $tagihan->bukti_bayar) }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-900 flex items-center underline">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        Lihat Bukti
                                                    </a>
                                                </div>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    Belum Bayar
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($tagihan->status == 'belum_bayar')
                                                
                                                <div class="flex items-center justify-end gap-2">
                                                    {{-- FORM KONFIRMASI (TERIMA) --}}
                                                    <form action="{{ route('tagihan.konfirmasi', $tagihan->id) }}" method="POST" onsubmit="return confirm('Yakin terima pembayaran ini? Status akan menjadi LUNAS.');">
                                                        @csrf
                                                        @method('PATCH')
                                                        
                                                        @if($tagihan->bukti_bayar)
                                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-indigo-700 focus:outline-none transition ease-in-out duration-150 shadow-sm" title="Verifikasi & Terima">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                Terima
                                                            </button>
                                                        @else
                                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-green-700 focus:outline-none transition ease-in-out duration-150 shadow-sm" title="Terima Pembayaran Tunai">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                Tunai
                                                            </button>
                                                        @endif
                                                    </form>

                                                    {{-- FORM TOLAK (Hanya muncul jika ada bukti) --}}
                                                    @if($tagihan->bukti_bayar)
                                                        <form action="{{ route('tagihan.tolak', $tagihan->id) }}" method="POST" onsubmit="return confirm('Tolak bukti ini? Penyewa harus upload ulang.');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-red-700 focus:outline-none transition ease-in-out duration-150 shadow-sm" title="Tolak Bukti">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                
                                                @if(!$tagihan->bukti_bayar)
                                                    {{-- Link Bayar Manual (Untuk Admin bantu bayarkan/lihat info) --}}
                                                    <a href="{{ route('tagihan.bayar', $tagihan->id) }}" class="block mt-2 text-gray-400 text-xs hover:text-gray-600">Lihat Info Bayar</a>
                                                @endif

                                            @else
                                                <span class="flex items-center justify-end text-green-600 font-bold text-sm">
                                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Selesai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                <p class="text-gray-500 text-base">Belum ada data tagihan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>