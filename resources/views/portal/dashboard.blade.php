<x-penyewa-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-indigo-600 rounded-lg shadow-xl p-6 mb-6 text-white">
                <h2 class="text-2xl font-bold">Halo, {{ $penyewa->nama_lengkap }}! 👋</h2>
                <p class="mt-2 opacity-90">Selamat datang di portal penyewa Kost Bu Adah.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    
                    {{-- LOGIKA TAMPILAN BERDASARKAN STATUS --}}

                    @if($timeline->count() > 0)
                        {{-- KONDISI 1: SUDAH JADI PENGHUNI (SEWA AKTIF) --}}
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Rincian Pembayaran Sewa (3 Bulan Ke Depan)</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($timeline as $item)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                                {{ $item['bulan'] }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                                {{ \Carbon\Carbon::parse($item['tanggal_jatuh_tempo'])->translatedFormat('d F Y') }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                Rp {{ number_format($item['jumlah'], 0, ',', '.') }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($item['status'] == 'lunas')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Lunas
                                                    </span>
                                                @elseif($item['status'] == 'menunggu_verifikasi')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Menunggu Verifikasi
                                                    </span>
                                                @elseif($item['status'] == 'estimasi')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-500">
                                                        Akan Datang
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Belum Bayar
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($item['status'] == 'lunas')
                                                    <a href="{{ route('tagihan.nota', $item['tagihan_id']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 flex items-center font-bold">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        Cetak Nota
                                                    </a>
                                                @elseif($item['status'] == 'menunggu_verifikasi')
                                                    <span class="text-gray-400 italic flex items-center cursor-not-allowed">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Sedang Diproses
                                                    </span>
                                                @elseif($item['status'] == 'estimasi')
                                                    <span class="text-gray-400">-</span>
                                                @else
                                                    <a href="{{ route('tagihan.bayar', $item['tagihan_id']) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                        Bayar Sekarang
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @elseif(isset($bookingPending) && $bookingPending)
                        {{-- KONDISI 2: BOOKING SUDAH DIKIRIM TAPI BELUM DI-ACC --}}
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Permintaan Booking Terkirim!</h3>
                            <p class="text-gray-600 mb-2 max-w-md mx-auto">
                                Anda telah mengajukan booking untuk kamar <strong>{{ $bookingPending->unit->name }}</strong>.
                            </p>
                            <div class="bg-yellow-50 px-6 py-3 rounded-lg border border-yellow-200 inline-block">
                                <p class="text-sm text-yellow-800 font-medium">
                                    Mohon tunggu konfirmasi dari Admin/Pemilik Kost. <br>
                                    Status sewa dan tagihan akan muncul di sini setelah booking disetujui.
                                </p>
                            </div>
                        </div>

                    @else
                        {{-- KONDISI 3: BELUM ADA APA-APA --}}
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Anda belum menyewa kamar</h3>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">Saat ini Anda belum memiliki sewa yang aktif. Silakan cari kamar yang tersedia dan ajukan booking untuk mulai menyewa.</p>
                            
                            <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-full font-bold text-white tracking-wide hover:bg-indigo-700 transition shadow-lg transform hover:-translate-y-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari Kost Sekarang
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-penyewa-layout>